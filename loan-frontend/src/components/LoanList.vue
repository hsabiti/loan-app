<template>
  <div>
    <h2 class="text-lg font-semibold mb-2">Your Loans</h2>
    <ul class="space-y-2">
      <li v-for="loan in loans" :key="loan.id" class="border p-4 rounded">
        <p>Amount: ${{ loan.amount }}</p>
        <p>Term: {{ loan.term }} months</p>
        <p>Status: {{ loan.status }}</p>
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
