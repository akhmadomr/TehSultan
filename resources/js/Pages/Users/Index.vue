<template>
  <AuthenticatedLayout>
    <Head title="Users Management" />
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="container mx-auto p-4">
              <!-- Button to show add user form -->
              <button @click="showAddUserForm = true" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 hover:bg-blue-600">Add User</button>

              <!-- Add User Form -->
              <div v-if="showAddUserForm" class="popup">
                <div class="popup-content">
                  <h2 class="text-2xl font-semibold mb-6">Add New User</h2>
                  <form @submit.prevent="addUser">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                      <div class="space-y-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input v-model="newUser.nama" type="text" id="nama" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required />
                      </div>

                      <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input v-model="newUser.email" type="email" id="email" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                      </div>

                      <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input v-model="newUser.username" type="text" id="username" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required />
                      </div>

                      <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select v-model="newUser.role" id="role" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                          <option value="">Select Role</option>
                          <option value="manager">Manager</option>
                          <option value="crewoutlet">Crew Outlet</option>
                          <option value="gudang">Gudang</option>
                        </select>
                      </div>

                      <div class="col-span-2 space-y-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea v-model="newUser.alamat" id="alamat" rows="3"
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                      </div>

                      <div class="space-y-2">
                        <label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input v-model="newUser.no_telp" type="text" id="no_telp" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                      </div>

                      <div class="space-y-2">
                        <label for="tanggal_bergabung" class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                        <input v-model="newUser.tanggal_bergabung" type="date" id="tanggal_bergabung" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                      </div>

                      <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input v-model="newUser.password" type="password" id="password" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required />
                      </div>

                      <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input v-model="newUser.password_confirmation" type="password" id="password_confirmation" 
                          class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required />
                      </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                      <button type="button" @click="showAddUserForm = false" 
                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                      </button>
                      <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Save
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Edit User Form -->
              <div v-if="showEditUserForm" class="popup">
                <div class="popup-content">
                  <h2 class="text-xl font-semibold mb-4">Edit User</h2>
                  <form @submit.prevent="updateUser">
                    <div class="mb-4">
                      <label for="nama" class="block text-sm font-medium">Name</label>
                      <input v-model="editUser.nama" type="text" id="edit-nama" class="w-full p-2 border border-gray-300 rounded mt-1" required />
                    </div>
                    <div class="mb-4">
                      <label for="edit-email" class="block text-sm font-medium">Email</label>
                      <input v-model="editUser.email" type="email" id="edit-email" class="w-full p-2 border border-gray-300 rounded mt-1" />
                    </div>
                    <div class="mb-4">
                      <label for="edit-alamat" class="block text-sm font-medium">Alamat</label>
                      <textarea v-model="editUser.alamat" id="edit-alamat" class="w-full p-2 border border-gray-300 rounded mt-1"></textarea>
                    </div>
                    <div class="mb-4">
                      <label for="edit-no_telp" class="block text-sm font-medium">No. Telepon</label>
                      <input v-model="editUser.no_telp" type="text" id="edit-no_telp" class="w-full p-2 border border-gray-300 rounded mt-1" />
                    </div>
                    <div class="mb-4">
                      <label for="edit-tanggal_bergabung" class="block text-sm font-medium">Tanggal Bergabung</label>
                      <input v-model="editUser.tanggal_bergabung" type="date" id="edit-tanggal_bergabung" class="w-full p-2 border border-gray-300 rounded mt-1" />
                    </div>
                    <div class="mb-4">
                      <label for="username" class="block text-sm font-medium">Username</label>
                      <input v-model="editUser.username" type="text" id="edit-username" class="w-full p-2 border border-gray-300 rounded mt-1" required />
                    </div>
                    <div class="mb-4">
                      <label for="role" class="block text-sm font-medium">Role</label>
                      <select v-model="editUser.role" id="edit-role" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                        <option value="manager">Manager</option>
                        <option value="crewoutlet">Crew Outlet</option>
                        <option value="gudang">Gudang</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label for="edit-password" class="block text-sm font-medium">New Password (leave blank if unchanged)</label>
                      <input v-model="editUser.password" type="password" id="edit-password" class="w-full p-2 border border-gray-300 rounded mt-1" />
                    </div>
                    <div class="mb-4">
                      <label for="edit-password_confirmation" class="block text-sm font-medium">Confirm New Password</label>
                      <input v-model="editUser.password_confirmation" type="password" id="edit-password_confirmation" class="w-full p-2 border border-gray-300 rounded mt-1" />
                    </div>
                    <div class="flex justify-end">
                      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
                      <button @click="showEditUserForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- User Details Popup -->
              <div v-if="showUserDetails" class="popup" @click.self="closeUserDetails">
                <div class="popup-content max-w-2xl">
                  <div class="flex justify-between items-center mb-4 pb-3 border-b">
                    <h2 class="text-xl font-semibold">User Details</h2>
                    <button @click="closeUserDetails" class="text-gray-500 hover:text-gray-700">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  
                  <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-3">
                      <div>
                        <label class="text-sm font-medium text-gray-600">Nama:</label>
                        <p class="mt-1">{{ selectedUser?.nama }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Email:</label>
                        <p class="mt-1">{{ selectedUser?.email }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Username:</label>
                        <p class="mt-1">{{ selectedUser?.username }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Role:</label>
                        <p class="mt-1">{{ selectedUser?.role }}</p>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div>
                        <label class="text-sm font-medium text-gray-600">Alamat:</label>
                        <p class="mt-1">{{ selectedUser?.alamat }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">No. Telepon:</label>
                        <p class="mt-1">{{ selectedUser?.no_telp }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Tanggal Bergabung:</label>
                        <p class="mt-1">{{ selectedUser?.tanggal_bergabung }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Status:</label>
                        <p class="mt-1">
                          <span :class="selectedUser?.is_active ? 'bg-green-500' : 'bg-red-500'" 
                                class="text-white px-2 py-1 rounded-full text-sm">
                            {{ selectedUser?.is_active ? 'Active' : 'Inactive' }}
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- User List Table -->
              <div v-if="users.length" class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
                <table class="min-w-full text-sm text-left text-gray-500">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="px-6 py-3">Nama</th>
                      <th class="px-6 py-3">Email</th>
                      <th class="px-6 py-3">Username</th>
                      <th class="px-6 py-3">Role</th>
                      <th class="px-6 py-3">Status</th>
                      <th class="px-6 py-3">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" 
                        :key="user.id" 
                        class="border-b hover:bg-gray-50 cursor-pointer"
                        @click="showUserDetail(user)">
                      <td class="px-6 py-4">{{ user.nama }}</td>
                      <td class="px-6 py-4">{{ user.email }}</td>
                      <td class="px-6 py-4">{{ user.username }}</td>
                      <td class="px-6 py-4">{{ user.role }}</td>
                      <td class="px-6 py-4">
                        <span :class="user.is_active ? 'bg-green-500' : 'bg-red-500'" class="text-white px-2 py-1 rounded-full">
                          {{ user.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                      <td class="px-6 py-4 space-x-2">
                        <button @click="prepareEditUser(user)" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</button>
                        <button @click="toggleStatus(user.id)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                          {{ user.is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="text-center text-gray-500 mt-4">
                No users found.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

export default {
  components: {
    AuthenticatedLayout,
    Head,
  },
  props: {
    auth: Object,
    users: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      authUser: this.auth,
      showAddUserForm: false,
      showEditUserForm: false,
      showUserDetails: false,
      selectedUser: null,
      newUser: {
        nama: '',
        email: '',
        username: '',
        role: '',
        alamat: '',
        no_telp: '',
        tanggal_bergabung: '',
        password: '',
        password_confirmation: '',
      },
      editUser: {
        id: '',
        nama: '',
        email: '',
        username: '',
        role: '',
        alamat: '',
        no_telp: '',
        tanggal_bergabung: '',
        password: '',
        password_confirmation: ''
      },
    };
  },
  // Remove mounted() and fetchUsers() since data is now passed as prop
  methods: {
    addUser() {
      axios.post(route('manager.users.store'), this.newUser)
        .then(() => {
          this.showAddUserForm = false;
          this.resetNewUser();
          // Refresh the page to get updated data
          window.location.reload();
        })
        .catch(error => {
          console.error('Error adding user:', error);
        });
    },
    // ...existing methods...
    // Update other methods to use window.location.reload() instead of manual data updates
    updateUser() {
      const formData = { ...this.editUser };
      
      if (!formData.password) {
        delete formData.password;
        delete formData.password_confirmation;
      }

      axios.patch(`/manager/users/${this.editUser.id}`, formData, {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        }
      })
      .then(response => {
        this.showEditUserForm = false;
        if (response.data.message) {
          alert(response.data.message);
        }
        window.location.reload();
      })
      .catch(error => {
        console.error('Error updating user:', error);
        alert(error.response?.data?.message || 'An error occurred while updating the user.');
      });
    },
    deleteUser(id) {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(route('manager.users.destroy', id), {
          onSuccess: () => {
            // The page will automatically refresh with Inertia
          },
        });
      }
    },
    toggleStatus(id) {
      axios.patch(route('manager.users.toggle-status', id))
        .then(response => {
          // Update the user status in the local data without page refresh
          const userIndex = this.users.findIndex(user => user.id === id);
          if (userIndex !== -1) {
            this.users[userIndex].is_active = !this.users[userIndex].is_active;
          }
        })
        .catch(error => {
          console.error('Error toggling status:', error);
        });
    },
    prepareEditUser(user) {
      this.editUser = {
        id: user.id,
        nama: user.nama,
        email: user.email,
        username: user.username,
        role: user.role,
        alamat: user.alamat,
        no_telp: user.no_telp,
        tanggal_bergabung: user.tanggal_bergabung,
        password: '',
        password_confirmation: ''
      };
      this.showEditUserForm = true;
    },
    showUserDetail(user) {
      this.selectedUser = { ...user };
      this.showUserDetails = true;
    },
    closeUserDetails() {
      this.showUserDetails = false;
      this.selectedUser = null;
    },
    resetNewUser() {
      this.newUser = {
        nama: '',
        email: '',
        username: '',
        role: '',
        alamat: '',
        no_telp: '',
        tanggal_bergabung: '',
        password: '',
        password_confirmation: '',
      };
    },
  },
};
</script>

<style scoped>
.popup {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
}

.popup-content {
  background: white;
  padding: 32px; /* Increased padding */
  border-radius: 8px;
  max-height: 90vh;
  width: 90%; /* Set width */
  max-width: 800px; /* Increased max-width */
  overflow-y: auto;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

button {
  padding: 8px 16px;
  border-radius: 4px;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #ddd;
}
</style>