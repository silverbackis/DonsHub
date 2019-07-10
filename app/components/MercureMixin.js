import { mapState } from 'vuex'
import { name as entitiesModuleName } from '~/.nuxt/bwstarter/core/storage/entities'

export default {
  data() {
    return {
      eventSource: null
    }
  },
  beforeDestroy() {
    if (this.eventSource) {
      this.eventSource.onmessage = null
      this.eventSource = null
    }
  },
  computed: {
    ...mapState({
      mercureHub: 'mercureHub'
    })
  },
  methods: {
    receiveEntityData({ data: json }) {
      const data = JSON.parse(json)
      this.$bwstarter.$storage.commit(
        'setEntity',
        [{ id: data['@id'], data }],
        entitiesModuleName
      )
      return data
    },
    mercureMount(topics) {
      // it appears a port is added to topic when we use API Platform, but not when we are adding from the commands...
      const mercureUrl = new URL(this.mercureHub)
      const topicBaseUrl = 'http://{domain}'
      topics.forEach(topic => {
        mercureUrl.searchParams.append('topic', topicBaseUrl + topic)
        mercureUrl.searchParams.append(
          'topic',
          topicBaseUrl + ':{port}' + topic
        )
      })
      this.eventSource = new EventSource(mercureUrl.toString())
      this.eventSource.onmessage = this.receiveEntityData
    }
  }
}
