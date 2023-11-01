   <div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">

       <div class="flex justify-between items-center ">
           <div class=" mb-0 border-b-0 border-solid ">
               <h5 class="mb-1 font-serif">Inventory</h5>
               <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                   {{ \Carbon\Carbon::now()->format('l, d M Y') }}
               </p>
           </div>
           <div class="flex items-center md:ml-auto md:pr-4">

           </div>
       </div>

       <div class="flex items-center h-screen justify-center -mx-3">
           <div class="flex-none w-full md:w-1/2 max-w-full px-3">
               <div
                   class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">

                   <div class="text-center mb-2">
                       <p class="font-serif">Edit Inventory</p>
                   </div>

                   <div class="flex-auto px-0 pt-0 pb-2">
                       <form wire:submit.prevent="update">
                           <!--E-mail input-->
                           <div class="relative mb-8">
                               <x-ui.input-default wire:model="name" label="Name" />
                               @error('name')
                                   <div class="text-red-500 text-sm">{{ $message }}</div>
                               @enderror
                           </div>
                           <div class="relative mb-8">
                               <x-ui.input-default wire:model="harga" id="harga" label="Price"
                                   x-data="{
                                       formatNumber: function(event) {
                                           const input = event.target;
                                           const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                           input.value = new Intl.NumberFormat('en-US').format(value);
                                       }
                                   }" x-on:input="formatNumber($event)" />
                               @error('harga')
                                   <div class="text-red-500 text-sm">{{ $message }}</div>
                               @enderror
                           </div>
                           <div class="relative mb-8">
                               <x-ui.input-default wire:model="current_stok" id="current_stok" label="Current Stock"
                                   x-data="{
                                       formatNumber: function(event) {
                                           const input = event.target;
                                           const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                           input.value = new Intl.NumberFormat('en-US').format(value);
                                       }
                                   }" x-on:input="formatNumber($event)" />
                               @error('stok')
                                   <div class="text-red-500 text-sm">{{ $message }}</div>
                               @enderror
                           </div>

                           <div class="relative mb-8">
                               <x-ui.input-default wire:model="stok" id="stok" label="Tambah Stock"
                                   x-data="{
                                       formatNumber: function(event) {
                                           const input = event.target;
                                           const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                           input.value = new Intl.NumberFormat('en-US').format(value);
                                       }
                                   }" x-on:input="formatNumber($event)" />
                               @error('stok')
                                   <div class="text-red-500 text-sm">{{ $message }}</div>
                               @enderror
                           </div>

                           <div class="flex gap-x-2">
                               <a href="{{ route('dashboard.inventory.index') }}"
                                   class="flex w-full justify-center gap-x-2 items-center rounded bg-gray-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-700 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-gray-800 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">

                                   Kembali
                               </a>

                               <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                                   formAction="update" />
                           </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>


   </div>
