<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Loan Details</h1>

    <!-- Loan Overview -->
    <div v-if="loan" class="space-y-3 border p-4 rounded shadow bg-white">
      <p><strong>Name:</strong> {{ loan.name }}</p>
      <p><strong>Amount:</strong> £{{ loan.amount }}</p>
      <p><strong>Annual Interest Rate:</strong> {{ (loan.annual_interest_rate * 100).toFixed(2) }}%</p>
      <p><strong>Type:</strong> {{ loan.repayment_type }}</p>
      <p><strong>Start Date:</strong> {{ loan.start_date }}</p>
      <p><strong>End Date:</strong> {{ loan.end_date }}</p>

      <div v-if="summary" class="mt-4 border-t pt-4">
        <h2 class="font-semibold">Summary</h2>
        <ul class="list-disc ml-6">
          <li v-for="(val, key) in summary" :key="key">
            <strong>{{ key.replaceAll('_', ' ') }}:</strong> £{{ Number(val).toLocaleString() }}
          </li>
        </ul>

        <!-- Loan Events List -->
        <div v-if="loan?.events?.length" class="mt-6">
        <h3 class="font-semibold mb-2">Loan Events</h3>
        <ul class="list-disc ml-6 space-y-1">
            <li v-for="event in loan.events" :key="event.id" class="flex justify-between items-center">
            <span>
                <strong>{{ event.type.replace('_', ' ') }}</strong>
                on {{ event.event_date }} 
                <template v-if="event.amount"> — £{{ event.amount }}</template>
                <template v-if="event.rate"> — New Rate: {{ (event.rate * 100).toFixed(2) }}%</template>
            </span>
            <button
                @click="deleteEvent(event.id)"
                class="text-sm text-red-600 hover:underline ml-4"
            >
                Delete
            </button>
            </li>
        </ul>
        </div>
      </div>
    </div>

    <!-- Events -->
    <div v-if="events.length" class="mt-6">
      <h2 class="text-lg font-semibold mb-2">Events</h2>
      <ul class="space-y-2">
        <li
          v-for="event in events"
          :key="event.id"
          class="border p-3 rounded bg-gray-50 flex justify-between items-center"
        >
          <div>
            <strong>{{ event.type.replace('_', ' ') }}</strong> on {{ event.event_date }}
            <template v-if="event.amount"> — £{{ event.amount }}</template>
            <template v-if="event.rate"> → {{ (event.rate * 100).toFixed(2) }}%</template>
          </div>
          <div class="flex gap-2">
            <button @click="deleteEvent(event.id)" class="text-red-600 hover:underline text-sm">Delete</button>
          </div>
        </li>
      </ul>
    </div>

    <!-- Event Actions -->
    <div class="mt-6 space-x-4">
      <button @click="showModal('part_payment')" class="bg-blue-600 text-white px-3 py-1 rounded">Add Part Payment</button>
      <button @click="showModal('rate_change')" class="bg-yellow-500 text-white px-3 py-1 rounded">Add Rate Change</button>
      <button @click="showModal('early_payoff')" class="bg-green-600 text-white px-3 py-1 rounded">Add Early Payoff</button>
    </div>

    <!-- Recalculate + Back -->
    <div class="mt-6">
      <button class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800" @click="recalculate">
        Recalculate
      </button>
      <router-link to="/" class="ml-4 text-sm text-gray-600 hover:underline">← Back</router-link>
    </div>
  </div>


  <!-- Event Modal -->
    <div v-if="modalType" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md relative">
        <h2 class="text-xl font-semibold mb-4 capitalize">Add {{ modalType.replace('_', ' ') }}</h2>

        <form @submit.prevent="submitEvent">
        <label class="block text-sm mb-1">Event Date</label>
        <input type="date" v-model="modalForm.event_date" required class="w-full mb-4 p-2 border rounded" />

        <div v-if="modalType === 'part_payment'">
            <label class="block text-sm mb-1">Amount</label>
            <input type="number" v-model.number="modalForm.amount" required class="w-full mb-4 p-2 border rounded" />
        </div>

        <div v-if="modalType === 'rate_change'">
            <label class="block text-sm mb-1">New Rate (%)</label>
            <input type="number" v-model.number="modalForm.rate" step="0.01" required class="w-full mb-4 p-2 border rounded" />
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" @click="closeModal" class="text-gray-600 hover:underline">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </div>
        </form>
    </div>
    </div>

</template>

<script>
import axios from '../lib/axios'

export default {
  data() {
    return {
      loan: null,
      summary: null,
      events: [],
      modalType: null,
      modalForm: {
        event_date: '',
        amount: '',
        rate: ''
        }
    }
  },
  async created() {
     await this.fetchLoan();
  },
  methods: {
    async fetchLoan() {
        const id = this.$route.params.id;
        try {
        const res = await axios.get(`/loans/${id}`, {
            headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });
        this.loan = res.data;
        } catch (err) {
        console.error('Failed to fetch loan', err);
        alert('Could not load loan details. Please go back and try again.');
        }
    },
    async recalculate() {
      try {
        const res = await axios.post(
          '/loan/recalculate',
          { loan_id: this.loan.id },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
          }
        )
        this.summary = res.data.summary
      } catch (err) {
        console.error('Recalculation failed', err)
        alert('Recalculation failed')
      }
    },
   showModal(type) {
    this.modalType = type
    this.modalForm = { event_date: '', amount: '', rate: '' }
    },

    closeModal() {
    this.modalType = null
    },

    async submitEvent() {
    try {
        await axios.post(`/loans/${this.loan.id}/events`, {
        type: this.modalType,
        event_date: this.modalForm.event_date,
        amount: this.modalType === 'part_payment' ? this.modalForm.amount : null,
        rate: this.modalType === 'rate_change' ? this.modalForm.rate : null
        }, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
        })

        this.closeModal()
        await this.fetchLoan()
        await this.recalculate()
        } catch (err) {
            console.error('Failed to save event', err)
            alert('Could not save event.')
        }
    },

    async deleteEvent(eventId) {
      try {
        await axios.delete(`/loans/${this.loan.id}/events/${eventId}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        })
        this.events = this.events.filter(e => e.id !== eventId)
        this.recalculate()
      } catch (err) {
        console.error('Failed to delete event', err)
        alert('Could not delete event.')
      }
    },
    async deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event?')) return;

    try {
        await axios.delete(`/loans/${this.loan.id}/events/${eventId}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
        });

        // Refetch loan + summary
        await this.fetchLoan();
        await this.recalculate();
    } catch (err) {
        console.error('Failed to delete event', err);
        alert('Could not delete event.');
    }
    },

  },
}
</script>
