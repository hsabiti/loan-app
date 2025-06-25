<template>
  <form @submit.prevent="submitLoan" class="space-y-4 bg-white p-4 rounded shadow">
    <h2 class="text-lg font-semibold">Create New Loan</h2>

    <!-- Loan Name / Reference -->
    <div>
      <label class="block text-sm font-medium">Loan Name / Reference</label>
      <input
        v-model="form.name"
        type="text"
        required
        class="w-full border p-2 rounded"
        placeholder="e.g. Bridging Loan June"
      />
    </div>

    <!-- Amount -->
    <div>
      <label class="block text-sm font-medium">Loan Amount (£)</label>
      <input
        v-model.number="form.amount"
        type="number"
        required
        class="w-full border p-2 rounded"
      />
    </div>

    <!-- Annual Interest Rate -->
    <div>
      <label class="block text-sm font-medium">Annual Interest Rate (%)</label>
      <input
        v-model.number="form.annual_interest_rate"
        type="number"
        step="0.01"
        required
        class="w-full border p-2 rounded"
      />
    </div>

    <!-- Repayment Type -->
    <div>
      <label class="block text-sm font-medium">Repayment Type</label>
      <select v-model="form.repayment_type" class="w-full border p-2 rounded">
        <option value="repayment">Repayment</option>
        <option value="interest-only">Interest-Only</option>
        <option value="interest-retained">Interest-Retained</option>
      </select>
    </div>

    <!-- Start and End Dates -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium">Start Date</label>
        <input
          v-model="form.start_date"
          type="date"
          required
          class="w-full border p-2 rounded"
        />
      </div>
      <div>
        <label class="block text-sm font-medium">End Date</label>
        <input
          v-model="form.end_date"
          type="date"
          required
          class="w-full border p-2 rounded"
        />
      </div>
    </div>

    <!-- Submit -->
    <div class="text-right">
      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Calculate & Save
      </button>
    </div>
  </form>
</template>

<script>
import axios from '../lib/axios'

export default {
  data() {
    return {
      form: {
        name: '',
        amount: '',
        annual_interest_rate: '',
        repayment_type: 'repayment',
        start_date: '',
        end_date: ''
      }
    }
  },
  methods: {
    async submitLoan() {
      try {
        await axios.post('/loans', this.form, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        this.$emit('loan-created')
        this.resetForm()
      } catch (error) {
        console.error('Error saving loan', error)
        alert('Failed to save loan. Check console.')
      }
    },
    resetForm() {
      this.form = {
        name: '',
        amount: '',
        annual_interest_rate: '',
        repayment_type: 'repayment',
        start_date: '',
        end_date: ''
      }
    }
  }
}
</script>
