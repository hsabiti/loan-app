<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Your Loans</h2>

    <ul class="space-y-4">
      <li v-for="loan in loans" :key="loan.id" class="border p-4 rounded shadow-sm">
        <div class="flex justify-between items-center mb-2">
          <p class="text-lg font-medium">Amount: ${{ loan.amount }}</p>
          <span
            class="px-2 py-1 rounded text-xs font-semibold"
            :class="{
              'bg-yellow-100 text-yellow-700': loan.status === 'pending',
              'bg-green-100 text-green-700': loan.status === 'approved',
              'bg-red-100 text-red-700': loan.status === 'rejected'
            }"
          >
            {{ loan.status }}
          </span>
        </div>
        <p>Term: {{ loan.term }} months</p>
        <p>Total Repayable: ${{ loan.total_repayable }}</p>
        <p>Monthly Repayment: ${{ loan.monthly_repayment }}</p>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    loans: Array
  }
}
</script>
