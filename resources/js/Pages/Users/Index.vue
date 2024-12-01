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
                  <h2 class="text-xl font-semibold mb-4">Add New User</h2>
                  <form @submit.prevent="addUser">
                    <div class="mb-4">
                      <label for="nama" class="block text-sm font-medium">Nama</label>
                      <input v-model="newUser.nama" type="text" id="nama" class="w-full p-2 border border-gray-300 rounded mt-1" required />
                    </div>
                    <div class="mb-4">
                      <label for="username" class="block text-sm font-medium">Username</label>
                      <input v-model="newUser.username" type="text" id="username" class="w-full p-2 border border-gray-300 rounded mt-1" required />
                    </div>
                    <div class="mb-4">
                      <label for="role" class="block text-sm font-medium">Role</label>
                      <select v-model="newUser.role" id="role" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                        <option value="manager">Manager</option>
                        <option value="crewoutlet">Crew Outlet</option>
                        <option value="gudang">Gudang</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label for="password" class="block text-sm font-medium">Password</label>
                      <input v-model="newUser.password" type="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-1" required />
                    </div>
                    <div class="mb-4">
                      <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                      <input v-model="newUser.password_confirmation" type="password" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded mt-1" required />
                    </div>
                    <div class="flex justify-end">
                      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
                      <button @click="showAddUserForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
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

              <!-- User List Table -->
              <div v-if="users.length" class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
                <table class="min-w-full text-sm text-left text-gray-500">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="px-6 py-3">Nama</th>
                      <th class="px-6 py-3">Username</th>
                      <th class="px-6 py-3">Role</th>
                      <th class="px-6 py-3">Status</th>
                      <th class="px-6 py-3">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
                      <td class="px-6 py-4">{{ user.nama }}</td>
                      <td class="px-6 py-4">{{ user.username }}</td>
                      <td class="px-6 py-4">{{ user.role }}</td>
                      <td class="px-6 py-4">
                        <span :class="user.is_active ? 'bg-green-500' : 'bg-red-500'" class="text-white px-2 py-1 rounded-full">
                          {{ user.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                      <td class="px-6 py-4 space-x-2">
                        <button @click="prepareEditUser(user)" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</button>
                        <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
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
      newUser: {
        nama: '',
        username: '',
        role: '',
        password: '',
        password_confirmation: '',
      },
      editUser: {
        id: '',
        nama: '',
        username: '',
        role: '',
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
      const form = useForm({
        ...this.editUser
      });

      if (!form.password) {
        delete form.password;
        delete form.password_confirmation;
      }

      form.put(route('manager.users.update', this.editUser.id), {
        onSuccess: () => {
          this.showEditUserForm = false;
          window.location.reload();
        },
        preserveScroll: true
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
        username: user.username,
        role: user.role,
        password: '',
        password_confirmation: ''
      };
      this.showEditUserForm = true;
    },
    resetNewUser() {
      this.newUser = {
        nama: '',
        username: '',
        role: '',
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
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  width: 400px;
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