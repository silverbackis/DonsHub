import ZonePrefixMixin from '~/components/ZonePrefixMixin'

export default function(endpoint) {
  function getTweetData($axios) {
    return $axios.$get(endpoint)
  }

  const Mixin = {
    mixins: [ZonePrefixMixin],
    data() {
      return {
        pollInterval: null
      }
    },
    computed: {
      tweets() {
        return this.tweetData['hydra:member']
      }
    },
    async asyncData({ $axios }) {
      return {
        endpoint,
        tweetData: await getTweetData($axios, endpoint)
      }
    },
    mounted() {
      this.updateTweetData()
    },
    beforeDestroy() {
      if (this.pollTimeout) {
        clearTimeout(this.pollTimeout)
      }
    },
    methods: {
      twitterLink(id) {
        return `https://twitter.com/Twitter/status/${id}?ref_src=twsrc%5Etfw`
      },
      async updateTweetData() {
        this.tweetData = await getTweetData(this.$axios, this.endpoint)
        this.pollTimeout = setTimeout(this.updateTweetData, 5000)
      }
    },
    head: {
      title: 'Twitter'
    }
  }

  return Mixin
}
