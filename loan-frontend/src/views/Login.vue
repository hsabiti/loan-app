<template>
  <div class="max-w-md w-full mx-auto mt-16 p-6 border border-gray-200 rounded-lg shadow-sm bg-white">
    <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>

    <form @submit.prevent="login" class="flex flex-col items-center">
      <div class="mb-4 w-4/5">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input v-model="email" type="email" class="w-full border border-gray-300 p-2 rounded" required />
      </div>
      <div class="mb-4 w-4/5">
        <label class="block text-sm font-medium mb-1">Password</label>
        <input v-model="password" type="password" class="w-full border border-gray-300 p-2 rounded" required />
      </div>

      <button class="bg-blue-600 hover:bg-blue-700 text-white w-4/5 py-2 rounded font-medium" type="submit">
        Login
      </button>
    </form>

    <p v-if="errorMessage" class="text-red-500 text-sm mt-4 text-center">{{ errorMessage }}</p>

    <p class="text-sm text-center mt-6">
      Don't have an account?
      <router-link to="/register" class="text-blue-600 hover:underline">Register here</router-link>
    </p>
  </div>
</template>

<script>
import axios from '../lib/axios'

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: ''
    }
  },
  methods: {
    async login() {
      this.errorMessage = ''

      try {
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password
        })

        localStorage.setItem('token', response.data.token)
        this.$router.push('/dashboard')
      } catch (err) {
        if (err.response && err.response.status === 401) {
          this.errorMessage = 'Incorrect email or password.'
        } else {
          this.errorMessage = 'Something went wrong. Please try again.'
        }

        console.warn('Login failed', err)
      }
    }
  }
}
</script>
