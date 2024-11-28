<template>
    <Card class="flex flex-col items-center justify-center cursor-pointer" @click="navigateToAssignedOrders">
        <div class="px-3 py-3 text-center">
            <h3 class="mb-2 text-base text-gray-500">Twoje nieskończone zamówienia</h3>
            <div class="text-4xl font-semibold mb-2">{{ ordersCount }}</div>
            <p class="text-sm text-gray-500">Kliknij, aby zobaczyć swoje przypisane zamówienia</p>
        </div>
    </Card>
</template>

<script>
export default {
  props: ['card'],

  data() {
    return {
      ordersCount: 0
    }
  },

  mounted() {
    this.fetchOrdersCount()
  },

  methods: {
    async fetchOrdersCount() {
      const response = await Nova.request().get('/nova-vendor/assignorders/assigned-orders-count')
      this.ordersCount = response.data.count
    },

    navigateToAssignedOrders() {
      window.location.href = '/resources/orders/lens/assign-orders'
    }
  }
}
</script>
