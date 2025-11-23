<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Motion } from '@motionone/vue';

const form = useForm({
    name: '',
    phone: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('name', 'phone', 'email', 'message');
        },
    });
};
</script>

<template>
    <GuestLayout title="Contact">
        <div class="flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8">
            <div class="w-full max-w-2xl">
                <!-- Intro + success message -->
                <Motion
                    class="mb-8 sm:mb-10 space-y-3 sm:space-y-4 text-center"
                    :initial="{ opacity: 0, y: 20 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.55, easing: 'ease-out' }"
                >
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Contact &amp; pre-order
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed mx-auto max-w-xl">
                        Send us a message if you want to pre-order Morna Mulberry, ask about
                        ingredients, or discuss partnership ideas.
                    </p>

                    <!-- Direct contact info (one row) -->
                    <div class="mt-3 text-xs sm:text-sm text-gray-700">
                        <p>
                            <span class="font-medium">Phone:</span>
                            <a
                                href="tel:+85570229710"
                                class="text-purple-700 hover:text-purple-900"
                            >
                                +855 70 229 710
                            </a>
                            <span class="mx-2 text-gray-400">•</span>
                            <span class="font-medium">Email:</span>
                            <a
                                href="mailto:chunchoeurn99@gmail.com"
                                class="text-purple-700 hover:text-purple-900"
                            >
                                chunchoeurn99@gmail.com
                            </a>
                        </p>
                    </div>

                    <Motion
                        v-if="$page.props.flash && $page.props.flash.success"
                        class="rounded-xl border border-green-200 bg-green-50 text-green-800 text-xs sm:text-sm px-4 py-3 mx-auto max-w-md"
                        :initial="{ opacity: 0, y: 8 }"
                        :animate="{ opacity: 1, y: 0 }"
                        :transition="{ duration: 0.3, easing: 'ease-out' }"
                    >
                        {{ $page.props.flash.success }}
                    </Motion>
                </Motion>

                <!-- Form -->
                <Motion
                    class="w-full rounded-2xl border bg-white shadow-sm p-4 sm:p-6 mx-auto"
                    :initial="{ opacity: 0, y: 24 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.6, delay: 0.1, easing: 'ease-out' }"
                >
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label
                                for="name"
                                class="block text-xs font-medium text-gray-700 mb-1"
                            >
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                autocomplete="name"
                            />
                            <p
                                v-if="form.errors.name"
                                class="mt-1 text-xs text-red-500"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Phone & Email -->
                        <div class="grid gap-4 sm:grid-cols-2">
                            <!-- Phone -->
                            <div>
                                <label
                                    for="phone"
                                    class="block text-xs font-medium text-gray-700 mb-1"
                                >
                                    Phone
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="text"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    autocomplete="tel"
                                />
                                <p
                                    v-if="form.errors.phone"
                                    class="mt-1 text-xs text-red-500"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label
                                    for="email"
                                    class="block text-xs font-medium text-gray-700 mb-1"
                                >
                                    Email
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    autocomplete="email"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="mt-1 text-xs text-red-500"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label
                                for="message"
                                class="block text-xs font-medium text-gray-700 mb-1"
                            >
                                Message
                            </label>
                            <textarea
                                id="message"
                                v-model="form.message"
                                rows="4"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            ></textarea>
                            <p
                                v-if="form.errors.message"
                                class="mt-1 text-xs text-red-500"
                            >
                                {{ form.errors.message }}
                            </p>
                        </div>

                        <div class="pt-2 flex justify-center">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-purple-600 text-white text-sm font-medium shadow-sm hover:bg-purple-700 disabled:opacity-60 transition-colors duration-200"
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Send message</span>
                                <span v-else>Sending...</span>
                            </button>
                        </div>
                    </form>
                </Motion>
            </div>
        </div>
    </GuestLayout>
</template>