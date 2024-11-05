import Card from './components/Card'

Nova.booting((app, store) => {
  app.component('order-count', Card)
})
