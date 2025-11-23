<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
});

const form = useForm({});

const resend = () => {
    form.post('/email/verification-notification');
};

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <AuthLayout title="Verify email">
        <Head title="Verify email" />

        <h1 class="text-xl text-gray-900 mb-2">
            Verify your email
        </h1>
        <p class="text-sm text-gray-600 mb-4">
            Thanks for signing up. Before getting started, please confirm your
            email address by clicking the link we just sent you.
        </p>

        <div
            v-if="props.status === 'verification-link-sent'"
            class="mb-4 rounded-md bg-green-50 px-3 py-2 text-xs text-green-700"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <div class="mt-8 space-y-3">
            <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-full bg-purple-600 px-4 py-2 text-sm text-white hover:bg-purple-700 disabled:opacity-60"
                :disabled="form.processing"
                @click="resend"
            >
                <span v-if="!form.processing">Resend verification email</span>
                <span v-else>Sending...</span>
            </button>

            <p class="text-center text-sm text-gray-600">
                Already verified or want to switch account?
                <button
                    type="button"
                    class="ml-1 text-purple-700 hover:text-purple-900"
                    @click="logout"
                >
                    Log out
                </button>
            </p>
        </div>
    </AuthLayout>
</template>
