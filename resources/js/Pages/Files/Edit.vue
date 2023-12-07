<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import BackToIndexLink from '@/Components/BackToIndexLink.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
import { ref } from 'vue';


const props = defineProps({
    file: Object, 
    image: String,
});

const form = useForm({
    id: props.file.id,
    name: props.file.name,
    image: props.file.image,
})

function updateFile() 
{
    router.post(`/dashboard/files/${props.file.id}`, {
    _method: 'put',
    name: form.name,
    image: form.image,
    }); 
}

const showConfirmDeleteFileModal = ref(false)

const confirmDeleteFile = () => {
      showConfirmDeleteFileModal.value = true;
}

const closeModal = () => {
  showConfirmDeleteFileModal.value = false;
}

const deleteFile = (id) => {
   form.delete(route('files.destroy', id), {
    onSuccess: () => closeModal()
   });
}

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

const confirmUpdateFileModal = ref(false);

const showConfirmUpdateFileModal = () => {
  confirmUpdateFileModal.value = true;
};

const closeConfirmUpdateModal = () => {
  confirmUpdateFileModal.value = false;
}

const updateFileWithConfirmation = () => {
  confirmUpdateFileModal.value = false;
  updateFile();
};

</script>

<template>
    <Head title="Edit" />
    <AuthenticatedLayout>
        
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit file</h2>
        </template>

        <div>
            <div class="flex items-center justify-center">
                <div class="mx-auto w-full max-w-[750px] bg-white p-8 rounded-xl m-8">
                    <div class="flex justify-between items-center gap-4 mt-2 mb-4">
                        
                        <BackToIndexLink/>
                        <DangerButton @click="confirmDeleteFile"> Delete </DangerButton>
                        
                        <Modal :show="showConfirmDeleteFileModal" @close="closeModal">           
                            <div class="p-4 md:p-5 text-center">               
                                <svg 
                                    class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" 
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
                                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>                
                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                    Are you sure to delete this File?
                                </h3>                       
                                <div class="mt-6 flex space-x-4 justify-center items-center">
                                    <DangerButton @click="deleteFile(file.id)">Delete</DangerButton>
                                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>

                    </div>
                    
                    <form 
                        class="p-4" 
                        @submit.prevent="showConfirmUpdateFileModal" 
                        enctype="multipart/form-data"
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
                            <label class="mb-5 block text-xl font-semibold text-gray-900">
                                File
                            </label>
                            <div>
                                <img 
                                    :src="image" 
                                    class="w-[100px] h-[100px] object-cover m-4 rounded-xl"
                                >
                            </div>                           
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
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
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
                        </div>
                        <div>

                            <button
                                type="submit"
                                class="hover:shadow-form w-full rounded-md bg-indigo-600 py-3 px-8 text-center text-base font-semibold text-white outline-none hover:bg-indigo-700">
                                Save changes
                            </button> 

                            <Modal :show="confirmUpdateFileModal" @close="closeConfirmUpdateModal">
                                <div class="mt-6 p-4 block space-x-4 justify-center items-center">
                                    <h3 class="mt-2 text-center text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Are you sure to update this File?
                                    </h3>  
                                    <div class="p-4 md:p-5 text-center space-x-4">
                                        <PrimaryButton @click="updateFileWithConfirmation">Yes, update</PrimaryButton>
                                        <SecondaryButton @click="closeConfirmUpdateModal">Cancel</SecondaryButton>
                                    </div>
                                </div>
                            </Modal> 
                            
                        </div>                                
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
