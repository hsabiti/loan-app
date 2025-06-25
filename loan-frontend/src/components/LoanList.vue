<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Your Loans</h2>

    <ul class="space-y-4">
      <li
        v-for="loan in loans"
        :key="loan.id"
        class="border p-4 rounded shadow-sm bg-white"
      >
        <div class="flex justify-between items-center mb-2">
          <router-link
            :to="`/loan/${loan.id}`"
            class="text-lg font-medium text-blue-700 hover:underline"
          >
            Loan #{{ loan.id }} — £{{ loan.amount }}
          </router-link>
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

        <!-- 💡 Loan Name / Reference -->
        <p class="italic text-sm text-gray-600 mb-1">
          {{ loan.name || 'No reference name given' }}
        </p>

        <p>Type: {{ loan.repayment_type }}</p>
        <p>Rate: {{ (loan.annual_interest_rate * 100).toFixed(2) }}%</p>
        <p>Start: {{ loan.start_date }} | End: {{ loan.end_date }}</p>

        <div class="mt-3 flex gap-3">
          <button
            @click="$emit('view-loan', loan)"
            class="text-blue-600 hover:underline text-sm"
          >
            View Details
          </button>
          <button
            @click="$emit('recalculate-loan', loan)"
            class="text-green-600 hover:underline text-sm"
          >
            Recalculate
          </button>
        </div>
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
