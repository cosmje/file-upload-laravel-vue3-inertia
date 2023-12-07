<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
import { ref, watch, defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    files: Array,
    pagination: String,
});

const search = ref('');

watch(search, value => {
    router.get('/dashboard/files', { search: value}, {
        preserveState: true,
        replace: true
    });
});

</script>

<template>
    <Head title="Files" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Files</h2>
        </template>
        
        <div class="max-w-[90%] ml-[5%] p-8">          
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div class="p-4 flex items-center justify-between flex-column md:flex-row flex-wrap space-y-4 md:space-y-0 py-4 bg-white">                   
                    
                    <label for="table-search" class="sr-only">
                        Search
                    </label>
                    <div class="relative">
                        <div 
                            class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none"
                        >
                        <svg 
                            class="w-4 h-4 text-gray-500" 
                            aria-hidden="true" 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 20 20"
                        >
                            <path 
                                stroke="currentColor" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2" 
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                            />
                        </svg>
                        </div>
                        <input 
                            v-model="search"
                            type="text" 
                            class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 
                            rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search for files"
                        >
                    </div>                    
                    
                    <Link 
                        :href="`/dashboard/files/create`"
                    >
                        <div class="flex justify-end rounded-lg m-2 px-8 py-2 font-medium bg-indigo-600 text-white">
                            Create
                        </div>
                    
                    </Link>                                   
                </div>

                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50">
                        <tr>                           
                            <th scope="col" class="px-6 py-3">
                                File
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Filename
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Size
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Extention
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Download
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr 
                            v-for="file in files" 
                            :key="file.id" 
                            class="bg-white border-b hover:bg-gray-50"
                        >                           
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                                <img                               
                                    :src="file.image" 
                                    alt="Uploaded image"
                                    class="w-[100px] h-[100px] object-cover rounded-md" 
                                >
                            </th>
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{file.name}}
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    {{ (file.size * 1.0).toFixed(2) + ' MB' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{file.extension}}
                            </td>

                            <!-- Download link as icon -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <a alt="Download icon" :href="`/dashboard/files/${file.id}/download`" download target="_blank" class="font-medium text-blue-600 hover:underline">
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke-width="1.5" 
                                            stroke="currentColor" 
                                            class="w-6 h-6"
                                        >
                                            <path 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" 
                                            />
                                        </svg>                                           
                                    </a>
                                </div>
                            </td>

                            <td class="px-6 py-4">  
                                <Link :href="`/dashboard/files/${file.id}/edit`" type="button" class="font-medium text-green-600 hover:underline">
                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        fill="none" viewBox="0 0 24 24" 
                                        stroke-width="1.5" 
                                        stroke="currentColor" 
                                        class="w-6 h-6"
                                    >
                                        <path 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round" 
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" 
                                        />
                                    </svg>
                                    Edit 
                                </Link>                      
                            </td>

                        </tr>
                    </tbody>                   
                </table>

                <!-- Pagination -->
                <div class="m-6 items-center">                       
                    <div v-html="pagination"></div>
                </div>
            </div>           
        </div>       
    </AuthenticatedLayout>
</template>


