<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    site_name: props.settings.site_name || '',
    logo: null,
    favicon: null,
    contact_email: props.settings.contact_email || '',
    contact_phone: props.settings.contact_phone || '',
    address: props.settings.address || '',
    footer_text: props.settings.footer_text || '',

    facebook_url: props.settings.facebook_url || '',
    instagram_url: props.settings.instagram_url || '',
    tiktok_url: props.settings.tiktok_url || '',
});

const submit = () => {
    const submitData = new FormData();
    
    if (form.site_name !== null && form.site_name !== undefined) {
        submitData.append('site_name', form.site_name.trim() || '');
    }
    submitData.append('contact_email', form.contact_email || '');
    submitData.append('contact_phone', form.contact_phone || '');
    submitData.append('address', form.address || '');
    submitData.append('footer_text', form.footer_text || '');

    // NEW: social links
    submitData.append('facebook_url', form.facebook_url || '');
    submitData.append('instagram_url', form.instagram_url || '');
    submitData.append('tiktok_url', form.tiktok_url || '');
    
    if (form.logo instanceof File) {
        submitData.append('logo', form.logo);
    }
    if (form.favicon instanceof File) {
        submitData.append('favicon', form.favicon);
    }

    form.post(route('admin.settings.general.update'), {
        data: submitData,
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.logo = null;
            form.favicon = null;
        }
    });
};

const onLogoChange = (event) => {
    if (event.target.files[0]) {
        form.logo = event.target.files[0];
        // Auto-submit when logo is selected
        submit();
    }
};

const onFaviconChange = (event) => {
    if (event.target.files[0]) {
        form.favicon = event.target.files[0];
        // Auto-submit when favicon is selected
        submit();
    }
};

const removeLogo = () => {
    if (confirm('Are you sure you want to remove the logo?')) {
        form.delete(route('admin.settings.logo.delete'), {
            preserveScroll: true,
            onSuccess: () => {
                // Refresh the page to get updated settings
                window.location.reload();
            }
        });
    }
};

const removeFavicon = () => {
    if (confirm('Are you sure you want to remove the favicon?')) {
        form.delete(route('admin.settings.favicon.delete'), {
            preserveScroll: true,
            onSuccess: () => {
                // Refresh the page to get updated settings
                window.location.reload();
            }
        });
    }
};
</script>

<template>
    <AdminLayout title="General Settings">
        <div class="space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    General Settings
                </h2>
                <p class="text-sm text-gray-600">
                    Manage your site branding, logo, and contact information.
                </p>
            </div>

            <form @submit.prevent="submit" class="max-w-4xl space-y-6">
                <!-- Brand & Logo Section -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Brand & Logo</h3>
                    
                    <!-- Site Name (Optional) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Site Name (Optional)
                        </label>
                        <input
                            v-model="form.site_name"
                            type="text"
                            class="block w-full rounded-lg border-gray-300 text-base shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="Leave empty to use only logo"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            If left empty, "Morna Mulberry" will be used as default.
                        </p>
                        <p v-if="form.errors.site_name" class="mt-1 text-xs text-red-500">
                            {{ form.errors.site_name }}
                        </p>
                    </div>

                    <!-- Logo Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Logo
                        </label>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <!-- Current Logo Preview -->
                            <div v-if="settings.logo_url" class="flex items-center gap-4">
                                <div class="h-20 w-20 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    <img 
                                        :src="settings.logo_url" 
                                        alt="Current logo"
                                        class="h-full w-full object-contain p-2"
                                    />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Current logo</p>
                                    <button
                                        type="button"
                                        @click="removeLogo"
                                        class="text-xs text-red-600 hover:text-red-800 mt-1"
                                    >
                                        Remove logo
                                    </button>
                                </div>
                            </div>

                            <!-- Upload New -->
                            <div class="flex-1">
                                <input
                                    type="file"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-purple-700"
                                    @change="onLogoChange"
                                />
                                <p class="mt-2 text-xs text-gray-500">
                                    Recommended: PNG with transparent background, max 2MB. Optimal size: 160x60px
                                </p>
                                <p v-if="form.errors.logo" class="mt-1 text-xs text-red-500">
                                    {{ form.errors.logo }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Favicon
                        </label>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <!-- Current Favicon Preview -->
                            <div v-if="settings.favicon_url" class="flex items-center gap-4">
                                <div class="h-12 w-12 bg-gray-100 rounded border border-gray-200 overflow-hidden">
                                    <img 
                                        :src="settings.favicon_url" 
                                        alt="Current favicon"
                                        class="h-full w-full object-contain p-1"
                                    />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Current favicon</p>
                                    <button
                                        type="button"
                                        @click="removeFavicon"
                                        class="text-xs text-red-600 hover:text-red-800 mt-1"
                                    >
                                        Remove favicon
                                    </button>
                                </div>
                            </div>

                            <!-- Upload New -->
                            <div class="flex-1">
                                <input
                                    type="file"
                                    accept=".ico,.png,.jpg,.jpeg"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-purple-700"
                                    @change="onFaviconChange"
                                />
                                <p class="mt-2 text-xs text-gray-500">
                                    .ico, .png, or .jpg format. Recommended: 32x32px or 64x64px, max 1MB
                                </p>
                                <p v-if="form.errors.favicon" class="mt-1 text-xs text-red-500">
                                    {{ form.errors.favicon }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    
                    <!-- existing email / phone / address fields ... -->

                    <!-- Social Links -->
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-800 mb-3">
                            Social Links
                        </h4>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Facebook URL
                                </label>
                                <input
                                    v-model="form.facebook_url"
                                    type="url"
                                    class="block w-full rounded-lg border-gray-300 text-base shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    placeholder="https://facebook.com/yourpage"
                                />
                                <p v-if="form.errors.facebook_url" class="mt-1 text-xs text-red-500">
                                    {{ form.errors.facebook_url }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Instagram URL
                                </label>
                                <input
                                    v-model="form.instagram_url"
                                    type="url"
                                    class="block w-full rounded-lg border-gray-300 text-base shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    placeholder="https://instagram.com/yourpage"
                                />
                                <p v-if="form.errors.instagram_url" class="mt-1 text-xs text-red-500">
                                    {{ form.errors.instagram_url }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    TikTok URL
                                </label>
                                <input
                                    v-model="form.tiktok_url"
                                    type="url"
                                    class="block w-full rounded-lg border-gray-300 text-base shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    placeholder="https://www.tiktok.com/@yourpage"
                                />
                                <p v-if="form.errors.tiktok_url" class="mt-1 text-xs text-red-500">
                                    {{ form.errors.tiktok_url }}
                                </p>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Leave any field empty to hide that social icon in the footer.
                        </p>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Footer Settings</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Footer Text
                        </label>
                        <textarea
                            v-model="form.footer_text"
                            rows="3"
                            class="block w-full rounded-lg border-gray-300 text-base shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="Copyright information or additional footer text"
                        ></textarea>
                        <p class="mt-2 text-xs text-gray-500">
                            This text will appear in the website footer.
                        </p>
                        <p v-if="form.errors.footer_text" class="mt-1 text-xs text-red-500">
                            {{ form.errors.footer_text }}
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-3 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 disabled:opacity-60 transition-colors duration-200 shadow-lg"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing">Save All Settings</span>
                        <span v-else>Saving Changes...</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>