 @forelse ($clients as $client)
     <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

         <!-- Name -->
         <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
             {{ $client->contact_name }}
         </td>

         <!-- Email -->
         <td class="px-4 py-3 max-w-[200px] truncate" title="{{ $client->contact_email }}">
             {{ $client->contact_email }}
         </td>

         <!-- Company -->
         <td class="px-4 py-3 max-w-[180px] truncate" title="{{ $client->company_name }}">
             {{ $client->company_name }}
         </td>

         <!-- Address -->
         <td class="px-4 py-3 max-w-[220px] truncate" title="{{ $client->company_address }}">
             {{ $client->company_address }}
         </td>

         <!-- Phone -->
         <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
             {{ $client->contact_phone_number }}
         </td>

         <!-- VAT -->
         <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
             {{ $client->company_vat }}
         </td>

         <td class="px-4 py-3 text-right">
             <div class="flex justify-end space-x-2">

                 <!-- Edit -->
                 <a href="{{ route('clients.edit', $client->id) }}"
                     class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                     </svg>
                 </a>

                 <!-- Delete -->
                 @can('delete')
                     <form class="deleteForm" action="{{ route('clients.destroy', $client->id) }}" method="POST">
                         @csrf
                         @method('DELETE')

                         <button type="button" class="deleteBtn text-red-600 hover:text-red-900">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>

                         </button>
                     </form>
                 @endcan

             </div>
         </td>

     </tr>
 @empty
     <tr>
         <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
             No clients found
         </td>
     </tr>
 @endforelse
