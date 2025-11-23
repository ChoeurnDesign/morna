<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    aboutPage: {
        type: Object,
        default: null,
    },
    teamMembers: {
        type: Array,
        default: () => [],
    },
});

// About Page Form
const aboutForm = useForm({
    title: props.aboutPage?.title || 'About Morna Mulberry',
    subtitle: props.aboutPage?.subtitle || '',
    story_text: props.aboutPage?.story_text || '',
    mission_text: props.aboutPage?.mission_text || '',
    vision_text: props.aboutPage?.vision_text || '',
    banner_image: null,
});

// Team Member Form
const teamMemberForm = useForm({
    name: '',
    role: '',
    bio: '',
    sort_order: 0,
    image: null,
});

const editingMember = ref(null);
const isAddingMember = ref(false);

// About Page Methods
const submitAbout = () => {
    aboutForm.post(route('admin.about.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            aboutForm.banner_image = null;
        },
    });
};

const onBannerChange = (event) => {
    if (event.target.files[0]) {
        aboutForm.banner_image = event.target.files[0];
        submitAbout();
    }
};

const removeBanner = () => {
    if (confirm('Are you sure you want to remove the banner image?')) {
        router.delete(route('admin.about.banner.delete'), {
            preserveScroll: true,
        });
    }
};

// Team Member Methods
const submitTeamMember = () => {
    if (editingMember.value) {
        teamMemberForm.put(route('admin.about.team-members.update', editingMember.value.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                resetTeamMemberForm();
            },
        });
    } else {
        teamMemberForm.post(route('admin.about.team-members.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                resetTeamMemberForm();
            },
        });
    }
};

const editTeamMember = (member) => {
    editingMember.value = member;
    teamMemberForm.name = member.name;
    teamMemberForm.role = member.role;
    teamMemberForm.bio = member.bio;
    teamMemberForm.sort_order = member.sort_order;
    isAddingMember.value = true;
};

const deleteTeamMember = (member) => {
    if (confirm(`Are you sure you want to delete ${member.name}?`)) {
        router.delete(route('admin.about.team-members.destroy', member.id), {
            preserveScroll: true,
        });
    }
};

const resetTeamMemberForm = () => {
    teamMemberForm.reset();
    editingMember.value = null;
    isAddingMember.value = false;
};

const onTeamMemberImageChange = (event) => {
    if (event.target.files[0]) {
        teamMemberForm.image = event.target.files[0];
    }
};
</script>

<template>
    <AdminLayout title="About Page Management">
        <div class="space-y-8">
            <!-- Header -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">
                    About Page Content
                </h2>
                <p class="text-sm text-gray-600">
                    Edit the story, mission, vision, and images for the public About page.
                </p>
            </div>

            <!-- About Page Content Form -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Page Content</h3>
                
                <form @submit.prevent="submitAbout" class="space-y-6">
                    <!-- Banner Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Banner Image
                        </label>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <!-- Current Banner Preview -->
                            <div v-if="aboutPage?.banner_image_path" class="flex items-center gap-4">
                                <div class="h-20 w-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    <img 
                                        :src="aboutPage.banner_image_path" 
                                        alt="Current banner"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Current banner</p>
                                    <button
                                        type="button"
                                        @click="removeBanner"
                                        class="text-xs text-red-600 hover:text-red-800 mt-1"
                                    >
                                        Remove banner
                                    </button>
                                </div>
                            </div>

                            <!-- Upload New -->
                            <div class="flex-1">
                                <input
                                    type="file"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-purple-700"
                                    @change="onBannerChange"
                                />
                                <p class="mt-2 text-xs text-gray-500">
                                    Recommended: Landscape orientation, max 2MB
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Page Title
                        </label>
                        <input
                            v-model="aboutForm.title"
                            type="text"
                            class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="About Morna Mulberry"
                        />
                        <p v-if="aboutForm.errors.title" class="mt-1 text-xs text-red-500">
                            {{ aboutForm.errors.title }}
                        </p>
                    </div>

                    <!-- Subtitle -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Subtitle
                        </label>
                        <input
                            v-model="aboutForm.subtitle"
                            type="text"
                            class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="Brief description of your brand"
                        />
                        <p v-if="aboutForm.errors.subtitle" class="mt-1 text-xs text-red-500">
                            {{ aboutForm.errors.subtitle }}
                        </p>
                    </div>

                    <!-- Story -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Our Story
                        </label>
                        <textarea
                            v-model="aboutForm.story_text"
                            rows="4"
                            class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="Tell your brand's story..."
                        ></textarea>
                        <p v-if="aboutForm.errors.story_text" class="mt-1 text-xs text-red-500">
                            {{ aboutForm.errors.story_text }}
                        </p>
                    </div>

                    <!-- Mission & Vision -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mission
                            </label>
                            <textarea
                                v-model="aboutForm.mission_text"
                                rows="3"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                placeholder="Your company's mission..."
                            ></textarea>
                            <p v-if="aboutForm.errors.mission_text" class="mt-1 text-xs text-red-500">
                                {{ aboutForm.errors.mission_text }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Vision
                            </label>
                            <textarea
                                v-model="aboutForm.vision_text"
                                rows="3"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                placeholder="Your company's vision..."
                            ></textarea>
                            <p v-if="aboutForm.errors.vision_text" class="mt-1 text-xs text-red-500">
                                {{ aboutForm.errors.vision_text }}
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 disabled:opacity-60 transition-colors duration-200 shadow-lg"
                            :disabled="aboutForm.processing"
                        >
                            <span v-if="!aboutForm.processing">Save Page Content</span>
                            <span v-else>Saving Changes...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Team Members Section -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                    <button
                        @click="isAddingMember = true"
                        class="inline-flex items-center px-4 py-2 rounded-full bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition-colors duration-200"
                    >
                        Add Team Member
                    </button>
                </div>

                <!-- Add/Edit Team Member Form -->
                <div v-if="isAddingMember" class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">
                        {{ editingMember ? 'Edit Team Member' : 'Add New Team Member' }}
                    </h4>
                    
                    <form @submit.prevent="submitTeamMember" class="space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Name
                                </label>
                                <input
                                    v-model="teamMemberForm.name"
                                    type="text"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Role
                                </label>
                                <input
                                    v-model="teamMemberForm.role"
                                    type="text"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    required
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bio
                            </label>
                            <textarea
                                v-model="teamMemberForm.bio"
                                rows="3"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                required
                            ></textarea>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sort Order
                                </label>
                                <input
                                    v-model="teamMemberForm.sort_order"
                                    type="number"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Profile Image
                                </label>
                                <input
                                    type="file"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-purple-700"
                                    @change="onTeamMemberImageChange"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button
                                type="button"
                                @click="resetTeamMemberForm"
                                class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 disabled:opacity-60 transition-colors duration-200"
                                :disabled="teamMemberForm.processing"
                            >
                                {{ editingMember ? 'Update' : 'Add' }} Team Member
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Team Members List -->
                <div v-if="teamMembers.length" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="member in teamMembers"
                        :key="member.id"
                        class="border border-gray-200 rounded-xl p-4"
                    >
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                                <img
                                    v-if="member.image_path"
                                    :src="member.image_path"
                                    :alt="member.name"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100 text-purple-600 font-semibold"
                                >
                                    {{ member.name.charAt(0) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">
                                    {{ member.name }}
                                </h4>
                                <p class="text-xs text-purple-600 font-medium">
                                    {{ member.role }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                    {{ member.bio }}
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    Order: {{ member.sort_order }}
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
                            <button
                                @click="editTeamMember(member)"
                                class="text-xs text-blue-600 hover:text-blue-800"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteTeamMember(member)"
                                class="text-xs text-red-600 hover:text-red-800"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-8">
                    <p class="text-sm text-gray-500">No team members added yet.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>