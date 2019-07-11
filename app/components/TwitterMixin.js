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
        tweets: null,
        loadingMore: false,
        loadedEarliest: false,
        beforeDateTime: null,
        page: 2
      }
    },
    methods: {
      getTweetsUrl() {
        return (
          endpoint +
          '?createdAt[before]=' +
          encodeURIComponent(this.beforeDateTime) +
          '&page=' +
          this.page
        )
      },
      async loadMore() {
        if (this.loadedEarliest) {
          return
        }
        if (this.loadingMore) {
          return
        }
        this.loadingMore = true
        const fetchUrl = this.getTweetsUrl()
        const { data } = await this.$axios.get(fetchUrl, {
          withCredentials: false
        })
        this.tweets.push(...data['hydra:member'])
        this.page = this.page + 1
        if (
          !data['hydra:member'].length ||
          data['hydra:view']['@id'] === data['hydra:view']['hydra:last']
        ) {
          this.loadedEarliest = true
        }
        this.loadingMore = false
        this.$nextTick(() => {
          if (window.twttr) {
            window.twttr.widgets.load()
          }
        })
      }
    },
    head: {
      title: 'Twitter'
    }
  }
  if (endpoint) {
    Mixin = Object.assign(Mixin, {
      async asyncData({ $axios }) {
        const tweetData = await getTweetData($axios, endpoint)
        return {
          endpoint,
          tweets: tweetData['hydra:member'],
          beforeDateTime: tweetData['hydra:member'][0].createdAt
        }
      }
    })
  }

  return Mixin
}
