<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// Get site settings from global Inertia props
const siteSettings = computed(() => page.props.siteSettings || {});

// Site settings with fallbacks
const siteName = computed(() => {
    return siteSettings.value.site_name || 'Morna Mulberry';
});

const contactEmail = computed(() => {
    return siteSettings.value.contact_email;
});

const contactPhone = computed(() => {
    return siteSettings.value.contact_phone;
});

const address = computed(() => {
    return siteSettings.value.address;
});

const footerText = computed(() => {
    return siteSettings.value.footer_text || `© ${new Date().getFullYear()} ${siteName.value}. All rights reserved.`;
});

const currentYear = computed(() => new Date().getFullYear());
</script>

<template>
    <footer class="border-t bg-white/90 mt-4">
        <div class="px-10 sm:px-14 lg:px-20 py-6 space-y-6">
            <!-- Info row: About + Links + Contact - 3 columns until 398px -->
            <div class="grid grid-cols-1 min-[398px]:grid-cols-3 gap-6 text-xs sm:text-sm text-gray-600">
                <!-- About Morna -->
                <div class="space-y-2">
                    <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">
                        About {{ siteName }}
                    </p>
                    <p class="leading-relaxed">
                        A student-led mulberry drink brand from Cambodia, focused on
                        real ingredients and thoughtful production.
                    </p>
                </div>

                <!-- Quick links -->
                <div class="space-y-2">
                    <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">
                        Quick links
                    </p>
                    <nav class="flex flex-wrap gap-2">
                        <Link
                            href="/"
                            class="hover:text-purple-700 transition-colors"
                        >
                            Home
                        </Link>
                        <span class="text-gray-300">•</span>
                        <Link
                            href="/product"
                            class="hover:text-purple-700 transition-colors"
                        >
                            Product
                        </Link>
                        <span class="text-gray-300">•</span>
                        <Link
                            href="/faq"
                            class="hover:text-purple-700 transition-colors"
                        >
                            FAQ
                        </Link>
                        <span class="text-gray-300">•</span>
                        <Link
                            href="/contact"
                            class="hover:text-purple-700 transition-colors"
                        >
                            Contact
                        </Link>
                    </nav>
                </div>

                <!-- Contact Information -->
                <div class="space-y-2">
                    <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">
                        Contact Information
                    </p>
                    <div class="leading-relaxed space-y-1">
                        <p v-if="contactEmail">{{ contactEmail }}</p>
                        <p v-if="contactPhone">{{ contactPhone }}</p>
                        <p v-if="address">{{ address }}</p>
                        <p class="text-xs text-gray-500 mt-2" v-if="!contactEmail && !contactPhone && !address">
                            For pre-orders, partnerships, or questions about the drink,
                            please use the contact form.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Partner logos - FIXED: Always 2 rows, images won't wrap -->
            <div class="space-y-4">
                <!-- Row 1: Implemented + Co-funded + Funded -->
                <div class="grid grid-cols-3 items-center justify-center gap-4 sm:gap-6 text-center">
                    <!-- Implemented by -->
                    <div class="space-y-1">
                        <p class="text-xs sm:text-sm text-gray-800 mb-2">
                            Implemented by
                        </p>
                        <div class="flex items-center justify-center gap-2 flex-nowrap">
                            <img
                                src="/images/sponsor/wonder-logo.png"
                                alt="Wonders within people logo"
                                class="h-4 sm:h-5 w-auto max-w-[60px] sm:max-w-[80px] object-contain"
                            />
                            <img
                                src="/images/sponsor/nicc-logo.png"
                                alt="NICC logo"
                                class="h-4 sm:h-5 w-auto max-w-[60px] sm:max-w-[80px] object-contain"
                            />
                        </div>
                    </div>

                    <!-- Co-funded by -->
                    <div class="space-y-1">
                        <p class="text-xs sm:text-sm text-gray-800 mb-2">
                            Co-funded by
                        </p>
                        <div class="flex items-center justify-center">
                            <img
                                src="/images/sponsor/childfund-korea.png"
                                alt="ChildFund Korea logo"
                                class="h-4 sm:h-5 w-auto max-w-[80px] sm:max-w-[100px] object-contain"
                            />
                        </div>
                    </div>

                    <!-- Funded by -->
                    <div class="space-y-1">
                        <p class="text-xs sm:text-sm text-gray-800 mb-2">
                            Funded by
                        </p>
                        <div class="flex items-center justify-center">
                            <img
                                src="/images/sponsor/koica-logo.png"
                                alt="KOICA logo"
                                class="h-4 sm:h-5 w-auto max-w-[80px] sm:max-w-[100px] object-contain"
                            />
                        </div>
                    </div>
                </div>

                <!-- Row 2: Dynamic Entrepreneur Spark only -->
                <div class="flex justify-center">
                    <div class="text-center space-y-1">
                        <p class="text-xs sm:text-sm text-gray-800 mb-1">
                            Entrepreneur Program
                        </p>
                        <img
                            src="/images/sponsor/dynamic.png"
                            alt="Dynamic Entrepreneur Spark logo"
                            class="h-6 sm:h-8 w-auto max-w-[120px] sm:max-w-[150px] object-contain mx-auto"
                        />
                    </div>
                </div>
            </div>

            <!-- Bottom bar -->
            <div
                class="border-t border-gray-100 pt-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs sm:text-sm text-gray-500"
            >
                <span>
                    {{ footerText }}
                </span>
                <span>Student startup demo site.</span>
            </div>
        </div>
    </footer>
</template>