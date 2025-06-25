<template>
  <div class="max-w-lg w-full mx-auto mt-16 p-6 border border-gray-200 rounded-lg shadow-sm bg-white">
    <h2 class="text-3xl font-bold mb-6 text-center">Register</h2>

    <form @submit.prevent="register" class="flex flex-col items-center">
      <div class="mb-4 w-4/5">
        <label class="block text-sm font-medium mb-1">Name</label>
        <input v-model="name" class="w-full border border-gray-300 p-2 rounded" required />
      </div>
      <div class="mb-4 w-4/5">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input v-model="email" type="email" class="w-full border border-gray-300 p-2 rounded" required />
      </div>
      <div class="mb-4 w-4/5">
        <label class="block text-sm font-medium mb-1">Password</label>
        <input v-model="password" type="password" class="w-full border border-gray-300 p-2 rounded" required />
      </div>
      <div class="mb-4 w-4/5">
        <label class="block text-sm font-medium mb-1">Confirm Password</label>
        <input v-model="password_confirmation" type="password" class="w-full border border-gray-300 p-2 rounded" required />
      </div>

      <button class="bg-blue-600 hover:bg-blue-700 text-white w-4/5 py-2 rounded font-medium" type="submit">
        Register
      </button>
    </form>

    <p v-if="errorMessage" class="text-red-500 text-sm mt-4 text-center">{{ errorMessage }}</p>

    <p class="text-sm text-center mt-6">
      Already have an account?
      <router-link to="/login" class="text-blue-600 hover:underline">Login here</router-link>
    </p>
  </div>
</template>


<script>
import axios from '../lib/axios'

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      errorMessage: ''
    }
  },
  methods: {
    async register() {
    this.errorMessage = ''

    try {
      // Step 1: Register user
      await axios.post('/register', {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      })

      // Step 2: Login user immediately
      const loginResponse = await axios.post('/login', {
        email: this.email,
        password: this.password
      })

      localStorage.setItem('token', loginResponse.data.token)
      this.$router.push('/dashboard')
    } catch (err) {
      if (err.response && err.response.data?.message) {
        this.errorMessage = err.response.data.message
      } else {
        this.errorMessage = 'Registration failed. Please check your inputs.'
      }
      console.error('Registration/Login failed:', err)
    }
  }

  }
}
</script>
