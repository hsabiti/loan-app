<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 font-sans">
    <!-- Header with Logout -->
    <div class="flex justify-between items-center p-4 border-b bg-white shadow">
      <h1 class="text-2xl font-bold">Loan Dashboard</h1>
      <button @click="logout" class="text-red-600 hover:underline">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="p-4 max-w-3xl mx-auto">
      <LoanForm @loan-created="fetchLoans" />
      <LoanList
        :loans="loans"
        @view-loan="goToLoan"
        @recalculate-loan="recalculateLoan"
      />
    </div>
  </div>
</template>

<script>
import LoanForm from '../components/LoanForm.vue'
import LoanList from '../components/LoanList.vue'
import axios from '../lib/axios'

export default {
  components: {
    LoanForm,
    LoanList
  },
  data() {
    return {
      loans: []
    }
  },
  methods: {
    async fetchLoans() {
      try {
        const res = await axios.get('/loans', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        this.loans = res.data
      } catch (error) {
        console.error('Failed to fetch loans', error)
      }
    },
    logout() {
      localStorage.removeItem('token')
      this.$router.push('/login')
    },
    goToLoan(loan) {
      console.log('Navigating to loan:', loan.id)
      this.$router.push(`/loan/${loan.id}`)
    },
    async recalculateLoan(loan) {
      try {
        const res = await axios.post(
          '/loan/recalculate',
          { loan_id: loan.id },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          }
        )
        const amount = Object.values(res.data.summary)[0]
        alert(`Updated interest to ${loan.end_date}: £${amount}`)
      } catch (e) {
        console.error('Recalculation failed:', e)
        alert('Could not recalculate this loan.')
      }
    }
  },
  mounted() {
    this.fetchLoans()
  }
}
</script>
