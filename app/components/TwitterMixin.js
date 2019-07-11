import ZonePrefixMixin from '~/components/ZonePrefixMixin'
import Tweets from '~/components/Tweets'

export default function(endpoint) {
  function getTweetData($axios) {
    return $axios.$get(endpoint, { withCredentials: false })
  }

  let Mixin = {
    mixins: [ZonePrefixMixin],
    components: {
      Tweets
    },
    data() {
      return {
        pollTimeout: null,
        tweetData: null
      }
    },
    computed: {
      tweets() {
        return this.tweetData ? this.tweetData['hydra:member'] : []
      }
    },
    head: {
      title: 'Twitter'
    }
  }
  if (endpoint) {
    Mixin = Object.assign(Mixin, {
      async asyncData({ $axios }) {
        return {
          endpoint,
          tweetData: await getTweetData($axios, endpoint)
        }
      },
      async mounted() {
        this.tweetData = await getTweetData(this.$axios, this.endpoint)
      }
    })
  }

  return Mixin
}
