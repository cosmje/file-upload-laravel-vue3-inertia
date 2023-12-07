<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BackToIndexLink from '@/Components/BackToIndexLink.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  name: null,
  image: null,
});

const isDragging = ref(false);

const onDragOver = (event) => {
  event.preventDefault();
  isDragging.value = true;
};

const onDragLeave = () => {
  isDragging.value = false;
};

const onDrop = (event) => {
  event.preventDefault();
  isDragging.value = false;

  const files = event.dataTransfer.files;
  if (files.length > 0) {
    form.image = files[0];
  }
};

function onFileChange(event) {
  const files = event.target.files;
  form.image = files[0];
}

function storeFile() {
  form.post('/dashboard/files');
}

</script>

<template>
    <Head title="Create" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create a new record
            </h2>
        </template>

        <div class="max-w-7xl m-8">
            <div class="flex items-center justify-center">
                <div class="mx-auto w-full max-w-[750px] bg-white p-8 rounded-xl">                   
                   
                    <BackToIndexLink />
                    
                    <form 
                        class="p-4" 
                        @submit.prevent="storeFile"
                    >
                        <div class="mb-5">
                            <label class="mb-3 block text-md font-medium text-[#07074D]">
                                File Name
                            </label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                name="name" 
                                id="name" 
                                placeholder="filename"
                                class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" 
                            />                       
                        </div>

                        <div class="mb-6 pt-4">
                            <label class="mb-5 block text-xl font-semibold text-[#07074D]">
                                Upload File
                            </label>

                        
                            <div class="flex items-center justify-center w-full">
                                <label
                                    for="dropzone-file"
                                    class="flex flex-col items-center justify-center w-full h-64 border-4 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50"
                                    :class="{ 'border-blue-400': isDragging, 'bg-green-100': form.image }" 
                                    @dragover.prevent="onDragOver"
                                    @dragleave.prevent="onDragLeave"
                                    @drop.prevent="onDrop"
                                >
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg 
                                            class="w-8 h-8 mb-4 text-gray-500" 
                                            aria-hidden="true" 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            fill="none" 
                                            viewBox="0 0 20 16"
                                        >
                                            <path 
                                                stroke="currentColor" 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                                            />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 ">
                                            <span class="font-semibold">
                                                Click to upload
                                            </span> 
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            SVG, PNG, JPG or GIF (MAX. 8MB)
                                        </p>
                                    </div>
                                
                                    <input 
                                        type="file"
                                        @input="onFileChange" 
                                        id="dropzone-file" 
                                        class="hidden" 
                                    />                                  
                                </label>                               
                            </div>

                            <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="m-4">
                                {{ form.progress.percentage }}%
                            </progress>
                            <Transition 
                                enter-from-class="opacity-0" 
                                leave-to-class="opacity-0" 
                                class="transition ease-in-out">
                                <p 
                                    v-if="form.recentlySuccessful" 
                                    class="m-4 text-md text-white bg-green-600 px-2 py-1 rounded-md">
                                    File has been successfully uploaded!
                                </p>
                            </Transition>

                        </div>
                        

                        <div>
                            <button 
                                class="hover:shadow-form w-full rounded-md bg-indigo-600 py-3 px-8 text-center text-base font-semibold text-white outline-none hover:bg-indigo-700"
                            >
                                Upload File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
