import { mapState } from 'vuex'
import { name as entitiesModuleName } from '~/.nuxt/bwstarter/core/storage/entities'

export default {
  data() {
    return {
      eventSource: null
    }
  },
  beforeDestroy() {
    this.eventSource.onmessage = null
    this.eventSource = null
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
    },
    mercureMount(topics) {
      const mercureUrl = new URL(this.mercureHub)
      const baseUrl = new URL(this.$store.state.baseUrl)
      const topicBaseUrl = baseUrl.protocol + '//' + baseUrl.hostname
      topics.forEach(topic => {
        mercureUrl.searchParams.append('topic', topicBaseUrl + topic)
      })
      this.eventSource = new EventSource(mercureUrl.toString())
      this.eventSource.onmessage = this.receiveEntityData
    }
  }
}
