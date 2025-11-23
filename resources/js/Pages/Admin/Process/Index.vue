<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import {
    PencilIcon,
    TrashIcon,
    PlusIcon,
    EyeIcon,
    EyeSlashIcon,
} from '@heroicons/vue/24/outline';

const page = usePage();
const steps = page.props.steps || [];

console.log('Process steps data:', steps);

// Check if route is a function before calling it
const getRoute = (name, params) => {
    // We use window.route as a fallback to bypass the ctx.route error
    // if Ziggy is globally exposed but not recognized in the setup scope.
    if (typeof window.route === 'function') {
        return window.route(name, params);
    }
    // Fallback in case route() is correctly injected by Ziggy
    if (typeof route === 'function') {
        return route(name, params);
    }
    console.error("Ziggy route helper is unavailable.");
    return name; // Return the route name as a path if the helper truly fails
};

const deleteStep = (step) => {
    if (confirm(`Are you sure you want to delete "${step.title}"?`)) {
        router.delete(getRoute('admin.process-steps.destroy', step.id));
    }
};

const toggleActive = (step) => {
    router.patch(getRoute('admin.process-steps.update', step.id), {
        is_active: !step.is_active,
    });
};
</script>

<template>
    <AdminLayout title="Process Steps">
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Process Steps
                    </h2>
                    <p class="text-sm text-gray-600">
                        Configure the step-by-step process that appears on the Process page for Morna Mulberry.
                    </p>
                </div>
                <Link
                    :href="getRoute('admin.process-steps.create')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                >
                    <PlusIcon class="w-4 h-4" />
                    Add Step
                </Link>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div v-if="steps.length > 0" class="divide-y divide-gray-200">
                    <div
                        v-for="step in steps"
                        :key="step.id"
                        class="p-6 hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-700 font-semibold">
                                    {{ step.step_number }}
                                </div>

                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ step.title }}
                                        </h3>
                                        <span
                                            v-if="!step.is_active"
                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full"
                                        >
                                            Inactive
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 line-clamp-2 max-w-2xl">
                                        {{ step.description }}
                                    </p>
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span v-if="step.icon_name">
                                            Icon: {{ step.icon_name }}
                                        </span>
                                        <span v-if="step.image_path">
                                            Has image
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    @click="toggleActive(step)"
                                    :class="[
                                        'p-2 rounded-lg transition-colors',
                                        step.is_active
                                            ? 'text-green-600 hover:bg-green-50'
                                            : 'text-gray-400 hover:bg-gray-100'
                                    ]"
                                    :title="step.is_active ? 'Deactivate' : 'Activate'"
                                >
                                    <EyeIcon v-if="step.is_active" class="w-4 h-4" />
                                    <EyeSlashIcon v-else class="w-4 h-4" />
                                </button>

                                <Link
                                    :href="getRoute('admin.process-steps.edit', step.id)"
                                    class="p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                >
                                    <PencilIcon class="w-4 h-4" />
                                </Link>

                                <button
                                    @click="deleteStep(step)"
                                    class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="p-12 text-center">
                    <div class="max-w-sm mx-auto space-y-4">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center">
                            <PlusIcon class="w-8 h-8 text-gray-400" />
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-semibold text-gray-900">No process steps yet</h3>
                            <p class="text-sm text-gray-600">
                                Get started by creating your first process step to show the farm-to-bottle journey.
                            </p>
                        </div>
                        <Link
                            :href="getRoute('admin.process-steps.create')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                        >
                            <PlusIcon class="w-4 h-4" />
                            Create First Step
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>