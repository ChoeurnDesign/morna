<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ProcessForm from './Form.vue';
import { useForm } from '@inertiajs/vue3';

// Helper function to resolve the route issue locally for the form submission
const getRoute = (name, params) => {
    if (typeof window.route === 'function') {
        return window.route(name, params);
    }
    if (typeof route === 'function') {
        return route(name, params);
    }
    console.error("Ziggy route helper is unavailable for:", name);
    return name;
};

const form = useForm({
    step_number: 1,
    title: '',
    description: '',
    icon_name: 'Sprout',
    image_path: '',
    is_active: true,
});

const submit = () => {
    // Corrected route name: admin.process-steps.store
    form.post(getRoute('admin.process-steps.store'));
};
</script>

<template>
    <AdminLayout title="Create Process Step">
        <ProcessForm
            :form="form"
            page-title="Create Process Step"
            page-description="Add a new step to the farm-to-bottle journey"
            submit-text="Create Step"
            @submit="submit"
        />
    </AdminLayout>
</template>