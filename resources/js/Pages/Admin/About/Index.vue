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
    // Only text fields are submitted here (banner_image is null unless selected, but we submit the other fields)
    aboutForm.post(route('admin.about.update'), {
        // Use forceFormData to handle potential file inputs even if they are null
        forceFormData: true, 
        preserveScroll: true,
        onSuccess: () => {
            // Reset the file input reference after successful upload/update
            aboutForm.banner_image = null;
        },
    });
};

const onBannerChange = (event) => {
    // This function handles instant banner image upload
    if (event.target.files[0]) {
        aboutForm.banner_image = event.target.files[0];
        // Trigger the form submit immediately after selecting the file
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
    // Use the form directly with forceFormData for proper file handling
    if (editingMember.value) {
        teamMemberForm.put(route('admin.about.team-members.update', editingMember.value.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                resetTeamMemberForm();
            },
            onError: (errors) => {
                console.log('Update errors:', errors);
            },
        });
    } else {
        teamMemberForm.post(route('admin.about.team-members.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                resetTeamMemberForm();
            },
            onError: (errors) => {
                console.log('Create errors:', errors);
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
    // Ensure the form panel is open when editing starts
    isAddingMember.value = true; 
};

const deleteTeamMember = (member) => {
    // Note: It's best practice to replace confirm() with a custom modal for better UX
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
        const file = event.target.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        
        if (file.size > maxSize) {
            alert('Image size must be less than 5MB. Please choose a smaller image.');
            event.target.value = ''; // Clear the file input
            teamMemberForm.image = null;
            return;
        }
        
        teamMemberForm.image = file;
    }
};
</script>

<template>
    <AdminLayout title="About Page Management">
        <div class="space-y-8">
            <!-- Header -->
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-gray-900">
                    About Page Content
                </h2>
                <p class="text-sm text-gray-600">
                    Edit the story, mission, vision, and images for the public About page.
                </p>
            </div>

            <!-- About Page Content Form -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
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
                                <div class="h-20 w-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-inner">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="title">
                            Page Title
                        </label>
                        <input
                            id="title"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="subtitle">
                            Subtitle
                        </label>
                        <input
                            id="subtitle"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="story">
                            Our Story
                        </label>
                        <textarea
                            id="story"
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
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mission">
                                Mission
                            </label>
                            <textarea
                                id="mission"
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
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="vision">
                                Vision
                            </label>
                            <textarea
                                id="vision"
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
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                    <button
                        @click="resetTeamMemberForm(); isAddingMember = true;"
                        class="inline-flex items-center px-4 py-2 rounded-full bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition-colors duration-200 shadow"
                    >
                        <!-- Plus icon using inline SVG for minimal dependencies -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Add Team Member
                    </button>
                </div>

                <!-- Add/Edit Team Member Form -->
<div v-if="isAddingMember" class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-200">
    <h4 class="text-md font-semibold text-gray-900 mb-4">
        {{ editingMember ? 'Edit Team Member' : 'Add New Team Member' }}
    </h4>
    
    <form @submit.prevent="submitTeamMember" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="member-name">
                    Name
                </label>
                <input
                    id="member-name"
                    v-model="teamMemberForm.name"
                    type="text"
                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    required
                />
                <p v-if="teamMemberForm.errors.name" class="mt-1 text-xs text-red-500">
                    {{ teamMemberForm.errors.name }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="member-role">
                    Role
                </label>
                <input
                    id="member-role"
                    v-model="teamMemberForm.role"
                    type="text"
                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    required
                />
                <p v-if="teamMemberForm.errors.role" class="mt-1 text-xs text-red-500">
                    {{ teamMemberForm.errors.role }}
                </p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2" for="member-bio">
                Bio
            </label>
            <textarea
                id="member-bio"
                v-model="teamMemberForm.bio"
                rows="3"
                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                required
            ></textarea>
            <p v-if="teamMemberForm.errors.bio" class="mt-1 text-xs text-red-500">
                {{ teamMemberForm.errors.bio }}
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="sort-order">
                    Sort Order
                </label>
                <input
                    id="sort-order"
                    v-model="teamMemberForm.sort_order"
                    type="number"
                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                />
                <p v-if="teamMemberForm.errors.sort_order" class="mt-1 text-xs text-red-500">
                    {{ teamMemberForm.errors.sort_order }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="member-image">
                    Profile Image
                </label>
                <input
                    id="member-image"
                    type="file"
                    accept="image/*"
                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-purple-700"
                    @change="onTeamMemberImageChange"
                />
                <p v-if="teamMemberForm.errors.image" class="mt-1 text-xs text-red-500">
                    {{ teamMemberForm.errors.image }}
                </p>
                <p v-if="editingMember && !teamMemberForm.image" class="mt-1 text-xs text-gray-500">
                    Leave blank to keep current image.
                </p>
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
                class="px-6 py-2 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 disabled:opacity-60 transition-colors duration-200 shadow"
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
                        class="border border-gray-200 rounded-xl p-4 shadow-sm"
                        :class="{'bg-purple-50/50 border-purple-300': editingMember && editingMember.id === member.id}"
                    >
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 flex-shrink-0 shadow-md">
                                <img
                                    v-if="member.image_path"
                                    :src="member.image_path"
                                    :alt="member.name"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100 text-purple-600 font-semibold text-xl"
                                >
                                    {{ member.name.charAt(0) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-bold text-gray-900 truncate">
                                    {{ member.name }}
                                </h4>
                                <p class="text-sm text-purple-600 font-medium">
                                    {{ member.role }}
                                </p>
                                <p class="text-xs text-gray-600 mt-2 line-clamp-2">
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
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteTeamMember(member)"
                                class="text-xs text-red-600 hover:text-red-800 font-medium"
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