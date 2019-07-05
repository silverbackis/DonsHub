<template>
  <section class="container">
    <div>
      <div class="links">
        <p>{{ data }}</p>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data: () => ({
    data: 'Loading...'
  }),
  async mounted() {
    const resourceUrl = this.$store.state.baseUrl + '/greetings'
    const { data, headers } = await this.$axios.get(resourceUrl)
    const mercureHub = headers.link.match(
      /<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/
    )[1]
    this.data = data
    const mercureUrl = new URL(mercureHub)
    mercureUrl.searchParams.append('topic', resourceUrl)
    const eventSource = new EventSource(mercureUrl + '/{id}')
    eventSource.onmessage = ({ data }) => {
      // data will be just an updated object. If deleted just the IRI is sent
      this.data = data
    }
  }
}
</script>
