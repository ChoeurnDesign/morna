<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    Bars3Icon,
    XMarkIcon,
    ChevronDownIcon,
} from '@heroicons/vue/24/outline';

const isMobileNavOpen = ref(false);
const isAccountOpen = ref(false);

const page = usePage();

// Get site settings from global Inertia props
const siteSettings = computed(() => page.props.siteSettings || {});

const navLinks = [
    { href: '/', label: 'Home' },
    { href: '/about', label: 'About' },
    { href: '/product', label: 'Product' },
    { href: '/process', label: 'Process' },
    { href: '/origin', label: 'Origin' },
    { href: '/faq', label: 'FAQ' },
    { href: '/contact', label: 'Contact' },
];

const isActive = (href) => page.url === href;

const user = computed(() => page.props.auth?.user || null);
const userInitial = computed(() =>
    user.value?.name ? user.value.name.charAt(0).toUpperCase() : 'M'
);

// Logo and site name from settings
const logoUrl = computed(() => {
    return siteSettings.value.logo_url;
});

const siteName = computed(() => {
    // Only show site name if there's NO logo
    return siteSettings.value.logo_url ? null : (siteSettings.value.site_name || 'Morna Mulberry');
});

const closeAll = () => {
    isMobileNavOpen.value = false;
    isAccountOpen.value = false;
};

// Logout with confirmation
const handleLogout = (event) => {
    event.preventDefault();
    
    if (confirm('Are you sure you want to logout?')) {
        router.post('/logout');
    }
};

// Mobile logout with confirmation
const handleMobileLogout = () => {
    if (confirm('Are you sure you want to logout?')) {
        router.post('/logout');
        closeAll();
    }
};
</script>

<template>
    <header
        class="sticky top-0 z-30 border-b border-purple-100/70 bg-gradient-to-b from-purple-50/80 to-white/90 backdrop-blur"
    >
        <div class="relative">
            <nav
                class="px-10 sm:px-14 lg:px-20 py-0 flex items-center justify-between gap-3"
            >
                <!-- Left: brand + desktop nav -->
                <div class="flex items-center gap-6">
                    <!-- Logo / brand -->
                    <div class="flex items-center h-full">
                        <Link href="/" class="flex items-center h-full">
                            <!-- Logo Image -->
                            <img 
                                v-if="logoUrl" 
                                :src="logoUrl" 
                                :alt="siteSettings.site_name || 'Morna Mulberry'"
                                class="h-16 w-auto object-contain"
                            />
                            <!-- Fallback: Show site name only when no logo -->
                            <div v-else class="flex items-center gap-2 h-full py-2">
                                <span
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-white text-sm"
                                >
                                    M
                                </span>
                                <span 
                                    class="font-medium text-gray-800 text-sm sm:text-base"
                                >
                                    {{ siteSettings.site_name || 'Morna Mulberry' }}
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop nav - Hide at 878px and below -->
                    <div
                        class="hidden custom-md:flex items-center gap-1 rounded-full bg-white/80 px-2 py-1 shadow-sm border border-purple-50"
                    >
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="link.href"
                            :class="[
                                'px-3 py-1 text-sm rounded-full font-medium transition-colors',
                                isActive(link.href)
                                    ? 'bg-purple-100 text-purple-800'
                                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
                            ]"
                        >
                            {{ link.label }}
                        </Link>
                    </div>
                </div>

                <!-- Right: account / auth (desktop) - Hide at 878px and below -->
                <div class="hidden custom-md:flex items-center gap-3 text-sm">
                    <!-- Logged in: account dropdown -->
                    <div v-if="user" class="relative">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-full bg-white/80 border border-gray-200 px-3 py-1.5 text-sm text-gray-700 shadow-sm hover:border-purple-200 hover:text-purple-700"
                            @click="isAccountOpen = !isAccountOpen"
                        >
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-xs text-white"
                            >
                                {{ userInitial }}
                            </span>
                            <span class="hidden sm:inline font-medium">
                                {{ user.name }}
                            </span>
                            <ChevronDownIcon
                                class="h-4 w-4 text-gray-400"
                            />
                        </button>

                        <!-- Dropdown -->
                        <Transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="opacity-0 translate-y-1"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-1"
                        >
                            <div
                                v-if="isAccountOpen"
                                class="absolute right-0 mt-2 w-44 rounded-xl bg-white border border-gray-100 shadow-lg py-2 text-sm z-40"
                            >
                                <p class="px-3 pb-1 text-[11px] uppercase tracking-wide text-gray-400">
                                    Account
                                </p>

                                <!-- Admin link only for admin role -->
                                <Link
                                    v-if="user.role === 'admin'"
                                    href="/admin"
                                    @click="isAccountOpen = false"
                                    class="block px-3 py-1.5 text-gray-700 hover:bg-purple-50 hover:text-purple-800"
                                >
                                    Admin dashboard
                                </Link>

                                <div class="border-t border-gray-100 mt-1 mb-1" />

                                <button
                                    @click="handleLogout"
                                    class="block w-full px-3 py-1.5 text-left text-red-600 hover:bg-red-50"
                                >
                                    Log out
                                </button>
                            </div>
                        </Transition>
                    </div>

                    <!-- Not logged in -->
                    <template v-else>
                        <Link
                            href="/login"
                            class="font-medium text-gray-700 hover:text-purple-700"
                        >
                            Log in
                        </Link>
                        <Link
                            href="/register"
                            class="font-medium text-purple-700 hover:text-purple-900"
                        >
                            Register
                        </Link>
                    </template>
                </div>

                <!-- Mobile menu button - Show at 878px and below -->
                <button
                    type="button"
                    class="custom-md:hidden inline-flex items-center justify-center rounded-full border border-gray-200 bg-white/80 p-1.5 text-gray-700 shadow-sm"
                    @click="isMobileNavOpen = !isMobileNavOpen"
                >
                    <span class="sr-only">Toggle navigation</span>
                    <Bars3Icon
                        v-if="!isMobileNavOpen"
                        class="h-7 w-7"
                    />
                    <XMarkIcon
                        v-else
                        class="h-7 w-7"
                    />
                </button>
            </nav>

            <!-- Mobile nav panel - Show at 878px and below -->
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div
                    v-if="isMobileNavOpen"
                    class="custom-md:hidden absolute inset-x-0 top-full bg-white/95 backdrop-blur border-t shadow-lg"
                >
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-1">
                        <!-- Main links -->
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="link.href"
                            @click="closeAll"
                            :class="[
                                'block rounded-lg px-3 py-2 text-sm font-medium',
                                isActive(link.href)
                                    ? 'bg-purple-50 text-purple-700'
                                    : 'text-gray-700 hover:bg-gray-50',
                            ]"
                        >
                            {{ link.label }}
                        </Link>

                        <!-- Account / auth -->
                        <div class="border-t border-gray-100 mt-3 pt-2 text-sm">
                            <template v-if="user">
                                <p class="px-3 pb-1 text-[11px] uppercase tracking-wide text-gray-400">
                                    Account
                                </p>

                                <div class="flex items-center gap-2 px-3 py-1.5">
                                    <span
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-xs text-white"
                                    >
                                        {{ userInitial }}
                                    </span>
                                    <span class="font-medium text-gray-800">
                                        {{ user.name }}
                                    </span>
                                </div>

                                <Link
                                    v-if="user.role === 'admin'"
                                    href="/admin"
                                    @click="closeAll"
                                    class="block px-3 py-1.5 text-gray-700 hover:bg-purple-50 hover:text-purple-800"
                                >
                                    Admin dashboard
                                </Link>

                                <button
                                    @click="handleMobileLogout"
                                    class="block w-full px-3 py-1.5 text-left text-red-600 hover:bg-red-50 mt-1"
                                >
                                    Log out
                                </button>
                            </template>

                            <template v-else>
                                <div class="flex gap-3 px-3 pt-1">
                                    <Link
                                        href="/login"
                                        @click="closeAll"
                                        class="font-medium text-gray-700 hover:text-purple-700"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href="/register"
                                        @click="closeAll"
                                        class="font-medium text-purple-700 hover:text-purple-900"
                                    >
                                        Register
                                    </Link>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </header>
</template>