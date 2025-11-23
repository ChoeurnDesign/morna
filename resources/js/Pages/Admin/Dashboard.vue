<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

// Receive props from Laravel controller instead of using placeholder data
const props = defineProps({
    stats: {
        type: Object,
        required: true,
        default: () => ({
            totalProducts: 0,
            totalHeroSlides: 0,
            totalFaqs: 0,
            totalContacts: 0,
            totalProcessSteps: 0,
            totalOrigins: 0,
        })
    },
    recentActivities: {
        type: Array,
        default: () => []
    }
});

// For debugging - remove this in production
console.log('Dashboard props:', props);
</script>

<template>
    <AdminLayout title="Dashboard">
        <div class="space-y-6">
            <!-- Welcome Section -->
            <div class="space-y-2">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                    Welcome to Morna Admin
                </h2>
                <p class="text-sm sm:text-base text-gray-600 max-w-2xl leading-relaxed">
                    Manage your mulberry drink business, track performance, and update content.
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Products Card -->
                <Link 
                    href="/admin/products" 
                    class="rounded-xl bg-white shadow-sm border p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 block"
                >
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Total Products
                        </div>
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ stats.totalProducts }}
                    </div>
                    <div class="mt-1 text-[11px] sm:text-xs text-purple-600 font-medium">
                        Manage products →
                    </div>
                </Link>

                <!-- Orders/Contacts Card -->
                <Link 
                    href="/admin/contacts" 
                    class="rounded-xl bg-white shadow-sm border p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 block"
                >
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Contact Messages
                        </div>
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ stats.totalContacts }}
                    </div>
                    <div class="mt-1 text-[11px] sm:text-xs text-green-600 font-medium">
                        View messages →
                    </div>
                </Link>

                <!-- Hero Slides Card -->
                <Link 
                    href="/admin/hero-slides" 
                    class="rounded-xl bg-white shadow-sm border p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 block"
                >
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Hero Slides
                        </div>
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ stats.totalHeroSlides }}
                    </div>
                    <div class="mt-1 text-[11px] sm:text-xs text-blue-600 font-medium">
                        Manage slides →
                    </div>
                </Link>

                <!-- FAQs Card -->
                <Link 
                    href="/admin/faqs" 
                    class="rounded-xl bg-white shadow-sm border p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 block"
                >
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide">
                            FAQs
                        </div>
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ stats.totalFaqs }}
                    </div>
                    <div class="mt-1 text-[11px] sm:text-xs text-orange-600 font-medium">
                        Manage FAQs →
                    </div>
                </Link>
            </div>

            <!-- Two Column Layout -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Quick Actions -->
                <div class="rounded-xl bg-white shadow-sm border p-4 sm:p-5">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <Link 
                            href="/admin/products/create" 
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200"
                        >
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Add New Product</div>
                                <div class="text-xs text-gray-500">Create a new drink variation</div>
                            </div>
                        </Link>

                        <Link 
                            href="/admin/hero-slides/create" 
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200"
                        >
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Add Hero Slide</div>
                                <div class="text-xs text-gray-500">Update homepage banner</div>
                            </div>
                        </Link>

                        <Link 
                            href="/admin/settings" 
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200"
                        >
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Site Settings</div>
                                <div class="text-xs text-gray-500">Update logo and site info</div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="rounded-xl bg-white shadow-sm border p-4 sm:p-5">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                        Recent Activity
                    </h3>
                    <div class="space-y-3">
                        <div 
                            v-for="(activity, index) in recentActivities" 
                            :key="index"
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-100"
                        >
                            <div 
                                class="w-8 h-8 rounded-lg flex items-center justify-center"
                                :class="{
                                    'bg-purple-100': activity.type === 'product',
                                    'bg-green-100': activity.type === 'contact',
                                    'bg-gray-100': activity.type === 'settings'
                                }"
                            >
                                <svg 
                                    class="w-4 h-4" 
                                    :class="{
                                        'text-purple-600': activity.type === 'product',
                                        'text-green-600': activity.type === 'contact',
                                        'text-gray-600': activity.type === 'settings'
                                    }" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-900">{{ activity.message }}</div>
                                <div class="text-xs text-gray-500">{{ activity.time }}</div>
                            </div>
                        </div>
                        
                        <div v-if="recentActivities.length === 0" class="text-center py-4">
                            <div class="text-sm text-gray-500">No recent activity</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>