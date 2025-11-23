<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Form from './Form.vue'; // Updated to import the local Form.vue
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    step: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    step_number: props.step.step_number,
    title: props.step.title,
    description: props.step.description,
    icon_name: props.step.icon_name || 'Sprout',
    image_path: props.step.image_path || '',
    is_active: props.step.is_active,
});

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

const submit = () => {
    // Corrected route name: admin.process-steps.update
    form.put(getRoute('admin.process-steps.update', props.step.id));
};
</script>

<template>
    <AdminLayout title="Edit Process Step">
        <Form
            :form="form"
            page-title="Edit Process Step"
            page-description="Update the process step details"
            submit-text="Update Step"
            :is-edit="true"
            @submit="submit"
        />
    </AdminLayout>
</template> 