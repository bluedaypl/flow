<template>
  <div class="text-center">


  <!-- <button v-if="next_status" :disabled="disabled" @click="click" class="disabled:bg-primary-400 disabled:text-slate-500 flex-shrink-0 shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm flex-shrink-0 ">
    Rozpocznij: {{ next_status.name }}
  </button> -->


  <LoadingButton
          v-if="status.next"
          dusk="order-status-next-button"
          type="button"
          @click="next"
          :loading="loading"
        >
        Rozpocznij: {{ status.next.name }}
</LoadingButton>

  <LoadingButton
          v-if="!status.next && status.order && status.order.ended_at == null"
          dusk="order-status-close-button"
          type="button"
          @click="next"
          :loading="loading"
        >
        Zakończ: {{ status.order.status.name }}
  </LoadingButton>



  </div>
</template>

<script>
export default {
  props: ['resourceName', 'resourceId', 'panel'],

  data() {
    return {
      status: {
        next: null,
        order: null,
      },
      loading: false,
    }
  },

  mounted() {
    this.loadStatus();
  },


  methods: {
    loadStatus() {
      Nova.request().get('/nova-vendor/order-status/status/'+this.resourceId).then(response => {
        this.status = response.data;
        var status = document.querySelector('div[dusk="status"] a');


        if(status) {
          status.href = '/resources/statuses/'+this.status.order.status.id;
          status.innerHTML = this.status.order.status.name;
        }

        // console.log(this.status.order);
        // if(this.status.order.ended_at) {
        //   alert('done');
        //   var done_at = document.querySelector('div[dusk="done_at"] p');
        //   if(done_at)
        //     done_at.innerHTML = this.status.order.ended_at;
        // }
      })
    },

    next() {
      if(this.loading) {
        return;
      }
      var self = this;
      this.loading = true;
      Nova.request().post('/nova-vendor/order-status/next-status/'+this.resourceId).then(response => {
        if(response.data.error) {
        Nova.error(response.data.error);
          self.loading = false;
          return;
        }
        // console.log(response);
        // if(response.data.ended_at != null) {
        //   return Nova.visit(`/resources/${this.resourceName}/${this.resourceId}`);
        //   // window.location.reload();
        // }

        return Nova.visit(`/resources/${this.resourceName}/${this.resourceId}`);


        // Nova.$emit('refresh-resources');
        // self.loadStatus();
        // setTimeout(function() {
        //   self.loading = false;
        // }, 1000);
      })
    },

  }
}
</script>
