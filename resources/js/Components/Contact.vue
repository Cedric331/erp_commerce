<template>
    <form @submit.prevent="handleSubmit">
        <div class="mb-6">
            <input
                v-model="form.name"
                type="text"
                placeholder="Votre name"
                class="w-full rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
            />
            <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
        </div>
        <div class="mb-6">
            <input
                v-model="form.email"
                type="email"
                placeholder="Votre Email"
                class="w-full rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
            />
            <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
        </div>
        <div class="mb-6">
            <input
                v-model="form.phone"
                type="text"
                placeholder="Votre Téléphone"
                class="w-full rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
            />
            <span v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</span>
        </div>
        <div class="mb-6">
      <textarea
          v-model="form.message"
          rows="6"
          placeholder="Votre Message"
          class="w-full resize-none rounded border border-[#f0f0f0] py-3 px-[14px] text-base text-body-color outline-none focus:border-primary focus-visible:shadow-none"
      ></textarea>
            <span v-if="form.errors.message" class="text-red-500 text-sm">{{ form.errors.message }}</span>
        </div>
        <div>
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded border border-primary bg-primary p-3 text-white transition hover:bg-opacity-90">
                Envoyer
            </button>
        </div>

        <div class="mt-5">
            <div
                v-if="form.wasSuccessful"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm"
                role="alert">
                <strong class="font-bold">Succès ! </strong>
                <span class="block sm:inline">Votre message a été envoyé avec succès.</span>
            </div>
        </div>

    </form>
</template>

<script>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3'

export default {
    setup() {

        const form = useForm({
            name: '',
            email: '',
            phone: '',
            message: '',
        });

        const handleSubmit = () => {
            form.clearErrors()

            form.post('/contact', {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset()
                },
            })
        };

        return {
            form,
            handleSubmit,
        };
    },
};
</script>

<style scoped>
</style>
