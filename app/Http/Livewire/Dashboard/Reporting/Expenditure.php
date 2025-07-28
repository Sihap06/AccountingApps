<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Expenditure as ModelsExpenditure;
use App\Models\LogActivityExpenditure;
use App\Models\PendingChange;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Expenditure extends Component
{
    use WithFileUploads;

    public $isAdd = false;
    public $isEdit = false;

    public $currentId;
    public $tanggal;
    public $jenis;
    public $total;
    public $image;
    public $existingImage;

    public $reason = '';
    public $showReasonModal = false;
    public $pendingAction = null;
    public $pendingActionData = null;

    public $totalAmount;

    public $selectedYear = '';
    public $selectedMonth = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $rules = [
        'tanggal' => 'required',
        'jenis' => 'required',
        'total' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
    ];

    protected $messages = [
        'image.image' => 'File harus berupa gambar.',
        'image.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, atau webp.',
        'image.max' => 'Ukuran gambar maksimal 10MB.',
        'image.uploaded' => 'Gagal upload gambar. File terlalu besar (max 10MB) atau ada masalah server.'
    ];

    public function resetValue()
    {
        $this->tanggal = '';
        $this->jenis = '';
        $this->total = '';
        $this->image = null;
        $this->existingImage = null;
    }

    public function store()
    {
        // Debug upload info
        if ($this->image) {
            $fileSize = $this->image->getSize();
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
            
            Log::info('Upload attempt:', [
                'size' => $fileSize,
                'size_mb' => $fileSizeMB . 'MB',
                'mime' => $this->image->getMimeType(),
                'extension' => $this->image->getClientOriginalExtension(),
                'php_upload_max' => ini_get('upload_max_filesize'),
                'php_post_max' => ini_get('post_max_size'),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            ]);
            
            // Check if file size might exceed server limits (typically 1MB for some hosts)
            if ($fileSizeMB > 1 && $fileSizeMB <= 10) {
                Log::warning('Large file upload detected', [
                    'size_mb' => $fileSizeMB,
                    'notice' => 'File may be rejected by web server if limit is 1MB'
                ]);
            }
        }
        
        try {
            $validateData = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            
            // Check if it's an upload error
            if (isset($errors['image']) && $this->image) {
                $fileSizeMB = round($this->image->getSize() / 1024 / 1024, 2);
                
                // Log detailed error info
                Log::error('Image validation failed', [
                    'errors' => $errors['image'],
                    'file_size_mb' => $fileSizeMB,
                    'upload_error_code' => $_FILES['image']['error'] ?? 'N/A',
                ]);
                
                // If file is between 1-10MB and we get uploaded error, suggest compression
                if ($fileSizeMB > 1 && str_contains(implode(' ', $errors['image']), 'uploaded')) {
                    $this->addError('image', "File ukuran {$fileSizeMB}MB mungkin ditolak server. Coba kompres gambar ke ukuran < 1MB atau hubungi administrator.");
                    return;
                }
            }
            
            throw $e;
        }
        $currencyString = preg_replace("/[^0-9]/", "", $this->total);
        $convertedCurrency = (int)$currencyString;
        $validateData['total'] = $convertedCurrency;
        $validateData['created_by'] = auth()->user()->id;

        // Handle image upload with compression
        if ($this->image) {
            $validateData['image'] = $this->compressAndStoreImage($this->image);
        }

        // Check if user is sysadmin (operator) - needs verification
        if (auth()->user()->role === 'sysadmin') {
            // Create pending change instead of direct create
            PendingChange::create([
                'changeable_type' => ModelsExpenditure::class,
                'changeable_id' => null,
                'action' => 'create',
                'old_data' => null,
                'new_data' => $validateData,
                'requested_by' => auth()->user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Perubahan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        } else {
            // Master admin can create directly
            ModelsExpenditure::create($validateData);

            LogActivityExpenditure::create([
                'user' => auth()->user()->name,
                'activity' => 'store',
                'jenis' => $validateData['jenis'],
                'new_jenis' => $validateData['jenis'],
                'new_tanggal' => $validateData['tanggal'],
                'new_total' => $validateData['total'],
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "successfully expenditure created.",
                'icon' => 'success'
            ]);
        }

        $this->setShowAdd();
    }

    public function edit($id)
    {
        $data = ModelsExpenditure::findOrFail($id);

        $this->tanggal = $data->tanggal;
        $this->jenis = $data->jenis;
        $this->total = $data->total;
        $this->existingImage = $data->image;
        $this->image = null; // Reset the file input

        $this->currentId = $id;

        if (!$this->isEdit) {
            $this->setShowEdit();
        }
    }

    public function update()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->total);
        $convertedCurrency = (int)$currencyString;
        $validateData['total'] = $convertedCurrency;

        $expend = ModelsExpenditure::findOrFail($this->currentId);

        // Handle image upload with compression
        if ($this->image) {
            // Delete old image if exists
            if ($expend->image) {
                Storage::delete('public/' . $expend->image);
            }
            $validateData['image'] = $this->compressAndStoreImage($this->image);
        } else {
            // Keep existing image if no new image uploaded
            $validateData['image'] = $expend->image;
        }

        // Check if user is sysadmin (operator) - needs verification
        if (auth()->user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'update';
            $this->pendingActionData = [
                'validateData' => $validateData,
                'expend' => $expend->toArray()
            ];
            $this->showReasonModal = true;
            $this->isEdit = false;
            return; // Return early to prevent resetting fields
        } else {
            // Master admin can update directly
            if ($this->jenis !== $expend->jenis || $this->tanggal !== $expend->tanggal || $validateData['total'] !== $expend->total) {
                $log = new LogActivityExpenditure();
                $log->user = auth()->user()->name;
                $log->activity = 'update';

                if ($this->tanggal !== $expend->tanggal) {
                    $log->old_tanggal = $expend->tanggal;
                    $log->new_tanggal = $this->tanggal;
                }

                if ($this->jenis !== $expend->jenis) {
                    $log->old_jenis = $expend->jenis;
                    $log->new_jenis = $this->jenis;
                }

                if ($this->total !== $expend->total) {
                    $log->old_total = $expend->total;
                    $log->new_total = $validateData['total'];
                }
                $log->save();
            }

            ModelsExpenditure::findOrFail($this->currentId)->update($validateData);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "successfully expenditure updated.",
                'icon' => 'success'
            ]);
        }

        $this->setShowEdit();
    }

    public function delete($id)
    {
        $expend = ModelsExpenditure::findOrFail($id);

        // Check if user is sysadmin (operator) - needs verification
        if (auth()->user()->role === 'sysadmin') {
            // Create pending change instead of direct delete
            PendingChange::create([
                'changeable_type' => ModelsExpenditure::class,
                'changeable_id' => $id,
                'action' => 'delete',
                'old_data' => $expend->toArray(),
                'new_data' => null,
                'requested_by' => auth()->user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Permintaan penghapusan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        } else {
            // Master admin can delete directly
            $expend->delete();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "successfully expenditure deleted.",
                'icon' => 'success'
            ]);
        }
    }

    public function setShowAdd()
    {
        $this->isAdd = !$this->isAdd;
        $this->isEdit = false;
    }

    public function setShowEdit()
    {
        $this->isEdit = !$this->isEdit;
        $this->isAdd = false;
    }

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = Carbon::now()->format('m');
    }

    public function render()
    {
        $query = ModelsExpenditure::whereMonth('tanggal', $this->selectedMonth)
            ->whereYear('tanggal', $this->selectedYear);

        if (auth()->user()->role != 'master_admin') {
            $query = $query->where('created_by', auth()->user()->id);
        }

        $data = $query->get();
        $this->totalAmount = $data->sum('total');

        $listYear = ['2023', '2024', '2025', '2026', '2027'];
        $listMonth = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return view('livewire.dashboard.reporting.expenditure', ['data' => $data, 'listMonth' => $listMonth, 'listYear' => $listYear]);
    }

    public function submitReason()
    {
        $this->validate([
            'reason' => 'required|min:10'
        ], [
            'reason.required' => 'Alasan harus diisi',
            'reason.min' => 'Alasan minimal 10 karakter'
        ]);

        if ($this->pendingAction === 'update') {
            $validateData = $this->pendingActionData['validateData'];
            $expend = $this->pendingActionData['expend'];

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => ModelsExpenditure::class,
                'changeable_id' => $this->currentId,
                'action' => 'update',
                'old_data' => $expend,
                'new_data' => array_merge($expend, $validateData),
                'reason' => $this->reason,
                'requested_by' => auth()->user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Perubahan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        }

        $this->closeReasonModal();
        $this->resetValue();
        $this->emit('refreshComponent');
    }

    /**
     * Compress and store image file
     */
    private function compressAndStoreImage($imageFile, $path = 'expenditure-images')
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.webp';
        $fullPath = $path . '/' . $filename;

        // Get original file size
        $originalSize = $imageFile->getSize();
        $originalSizeMB = round($originalSize / 1024 / 1024, 2);

        // Load and compress image
        $image = Image::make($imageFile->getRealPath());

        // Determine max dimensions and quality based on file size
        $maxDimension = 1200;
        $quality = 80;

        // If file is larger than 1MB, apply more aggressive compression
        if ($originalSizeMB > 1) {
            $maxDimension = 1000;
            $quality = 70;
            
            // For files larger than 2MB, be even more aggressive
            if ($originalSizeMB > 2) {
                $maxDimension = 800;
                $quality = 60;
            }
        }

        // Resize if too large
        if ($image->width() > $maxDimension || $image->height() > $maxDimension) {
            $image->resize($maxDimension, $maxDimension, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convert to WebP and compress
        $compressedImage = $image->encode('webp', $quality);

        // Check compressed size
        $compressedSize = strlen($compressedImage);
        $compressedSizeMB = round($compressedSize / 1024 / 1024, 2);

        Log::info('Image compression result:', [
            'original_size_mb' => $originalSizeMB,
            'compressed_size_mb' => $compressedSizeMB,
            'quality' => $quality,
            'max_dimension' => $maxDimension,
            'compression_ratio' => round((1 - $compressedSize / $originalSize) * 100, 2) . '%'
        ]);

        // Store the compressed image
        Storage::disk('public')->put($fullPath, $compressedImage);

        return $fullPath;
    }

    public function removeImage()
    {
        if ($this->currentId) {
            $expend = ModelsExpenditure::findOrFail($this->currentId);
            if ($expend->image) {
                Storage::delete('public/' . $expend->image);
                $expend->update(['image' => null]);
                $this->existingImage = null;

                $this->dispatchBrowserEvent('swal', [
                    'title' => 'Success',
                    'text' => "Image berhasil dihapus.",
                    'icon' => 'success'
                ]);
            }
        }
    }

    public function closeReasonModal()
    {
        $this->showReasonModal = false;
        $this->reason = '';
        $this->pendingAction = null;
        $this->pendingActionData = null;
    }
}
