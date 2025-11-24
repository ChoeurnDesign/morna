<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    Image,
    Info,
    Package,
    Workflow,
    Map,
    HelpCircle,
    MessagesSquare,
    Settings as SettingsIcon,
    Menu,
    X,
} from 'lucide-vue-next';

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const page = usePage();
const isSidebarOpen = ref(false);

// Site settings / logo
const siteSettings = computed(() => page.props.siteSettings || {});
const logoUrl = computed(() => siteSettings.value.logo_url);

const navItems = [
    { label: 'Dashboard',      href: '/admin',             icon: LayoutDashboard },
    { label: 'Hero slides',    href: '/admin/hero-slides', icon: Image },
    { label: 'About page',     href: '/admin/about',       icon: Info },
    { label: 'Products',       href: '/admin/products',    icon: Package },
    { label: 'Process steps',  href: '/admin/process-steps',icon: Workflow },
    { label: 'Origins',        href: '/admin/origins',     icon: Map },
    { label: 'FAQs',           href: '/admin/faqs',        icon: HelpCircle },
    { label: 'Contacts',       href: '/admin/contacts',    icon: MessagesSquare },
    { label: 'Settings',       href: '/admin/settings',    icon: SettingsIcon },
];

const currentUrl = computed(() => page.url);

const closeSidebar = () => {
    isSidebarOpen.value = false;
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="hidden md:flex md:flex-col w-64 bg-white border-r">
            <div class="h-14 flex items-center px-4 border-b">
                <Link href="/" class="flex items-center gap-2">
                    <img
                        v-if="logoUrl"
                        :src="logoUrl"
                        :alt="siteSettings.site_name || 'Morna Mulberry'"
                        class="h-10 w-auto"
                    />
                    <div v-else class="flex items-center gap-2">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-white text-sm font-bold"
                        >
                            M
                        </span>
                        <span class="font-semibold text-gray-800 text-sm">
                            {{ siteSettings.site_name || 'Morna Admin' }}
                        </span>
                    </div>
                </Link>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-2 rounded-lg px-3 py-2 transition',
                        currentUrl === item.href ||
                        currentUrl.startsWith(item.href + '/')
                            ? 'bg-purple-50 text-purple-700 font-medium'
                            : 'text-gray-600 hover:bg-gray-100',
                    ]"
                >
                    <component :is="item.icon" class="w-4 h-4" />
                    <span>{{ item.label }}</span>
                </Link>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col relative">
            <header
                class="h-14 bg-white border-b flex items-center justify-between px-4 md:px-6 z-10"
            >
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="md:hidden inline-flex items-center justify-center rounded-full border border-gray-200 bg-white p-1.5 text-gray-700 shadow-sm"
                        @click="isSidebarOpen = !isSidebarOpen"
                    >
                        <span class="sr-only">Toggle admin navigation</span>
                        <Menu v-if="!isSidebarOpen" class="h-5 w-5" />
                        <X v-else class="h-5 w-5" />
                    </button>

                    <h1 class="text-base md:text-lg font-semibold text-gray-800">
                        {{ title }}
                    </h1>
                </div>

                <div class="flex items-center gap-4 text-xs md:text-sm text-gray-600">
                    <div class="hidden sm:block">
                        <span class="font-medium text-gray-800">
                            {{ page.props?.auth?.user?.name || 'Admin' }}
                        </span>
                    </div>

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="inline-flex items-center px-3 py-1.5 rounded-full border border-gray-200 text-xs font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="-translate-x-full"
                enter-to-class="translate-x-0"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="translate-x-0"
                leave-to-class="-translate-x-full"
            >
                <div
                    v-if="isSidebarOpen"
                    class="md:hidden fixed inset-0 z-20 bg-black/30"
                    @click="closeSidebar"
                >
                    <nav
                        class="w-64 h-full bg-white border-r shadow-2xl p-0 space-y-1 text-sm overflow-y-auto"
                        @click.stop
                    >
                        <div class="h-14 flex items-center px-4 border-b">
                            <Link href="/" class="flex items-center gap-2">
                                <img
                                    v-if="logoUrl"
                                    :src="logoUrl"
                                    :alt="siteSettings.site_name || 'Morna Mulberry'"
                                    class="h-10 w-auto"
                                />
                                <div v-else class="flex items-center gap-2">
                                    <span
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-white text-sm font-bold"
                                    >
                                        M
                                    </span>
                                    <span class="font-semibold text-gray-800 text-sm">
                                        {{ siteSettings.site_name || 'Morna Admin' }}
                                    </span>
                                </div>
                            </Link>
                        </div>

                        <div class="px-3 py-2 space-y-1">
                            <Link
                                v-for="item in navItems"
                                :key="item.href"
                                :href="item.href"
                                @click="closeSidebar"
                                :class="[
                                    'flex items-center gap-2 rounded-lg px-3 py-2 transition',
                                    currentUrl === item.href ||
                                    currentUrl.startsWith(item.href + '/')
                                        ? 'bg-purple-50 text-purple-700 font-medium'
                                        /* 🛑 Mobile Nav Item: Added hover:bg-gray-100 to show a slight dark gray on hover/tap 🛑 */
                                        : 'text-gray-600 hover:bg-gray-100', 
                                ]"
                            >
                                <component :is="item.icon" class="w-4 h-4" />
                                <span>{{ item.label }}</span>
                            </Link>
                        </div>
                    </nav>
                </div>
            </Transition>

            <main class="flex-1 p-4 md:p-6">
                <div class="max-w-6xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>