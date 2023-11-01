 <div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">

     <div class="flex justify-between items-center ">
         <div class=" mb-0 border-b-0 border-solid ">
             <h5 class="mb-1 font-serif">Hello Admin, </h5>
             <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                 {{ \Carbon\Carbon::now()->format('l, d M Y') }}
             </p>
         </div>
         <div class="flex items-center md:ml-auto md:pr-4">

         </div>
     </div>
     <div class="flex justify-center">
         <div class="w-full max-w-full mt-6 md:w-8/12">
             <div
                 class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1 mt-6">
                 <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                     <div class="flex items-center">
                         <p class="mb-0 dark:text-white/80">Transaction</p>
                     </div>
                 </div>
                 <div class="flex-auto p-6 mt-4">
                     <form wire:submit.prevent="submit">
                         <div class="flex flex-wrap -mx-3">
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.input label="Service" name="service" id="service" />
                             </div>
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.input label="Biaya" name="biaya" id="biaya" />
                             </div>
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.select label="Modal" name="modal" id="modal" :options="['ya' => 'Dengan Modal', 'tidak' => 'Tanpa Modal']" />
                             </div>
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.select label="Teknisi" name="teknisi" id="teknisi" :options="['ya' => 'Andi', 'tidak' => 'Topek']" />
                             </div>
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.select label="Sparepart" name="sparepart" id="sparepart" :options=""
                                     search />
                             </div>
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.select label="Metode Pembayaran" name="metode_pembayaran" id="metode_pembayaran"
                                     search :options="['al' => 'Alabama', 'wi' => 'Wisconsin']" />
                             </div>
                             <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                 <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                                     formAction="submit" />
                             </div>

                         </div>
                     </form>

                 </div>
             </div>
         </div>
     </div>
 </div>
