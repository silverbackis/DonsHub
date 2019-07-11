<template>
  <div>
    <div v-if="!tweets" class="section has-text-centered">
      <div class="loader is-medium is-primary">
        <span class="is-sr-only">Loading</span>
      </div>
    </div>
    <div v-else class="live-tweets">
      <tweets :tweets="tweets" />

      <load-more-button
        v-if="!loadedEarliest"
        :disabled="loadingMore"
        @click="loadMore"
      >
        {{ loadedEarliest ? 'Go to Twitter' : 'Load more' }}
      </load-more-button>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import TwitterMixin from '~/components/TwitterMixin'
import MercureMixin from '~/components/MercureMixin'
import LoadMoreButton from '~/components/LoadMoreButton'

export default {
  components: { LoadMoreButton },
  mixins: [TwitterMixin(null, true), MercureMixin],
  data() {
    return {
      beforeDateTime: null,
      loadingMore: false,
      loadedEarliest: false,
      page: 1
    }
  },
  computed: {
    ...mapGetters({
      matchDateTime: 'matchDateTime',
      gatesDateTime: 'gatesDateTime'
    })
  },
  async mounted() {
    this.afterDateTime = this.gatesDateTime
      .utc()
      .subtract(1, 'hours')
      .format()
    this.beforeDateTime = this.matchDateTime.format('YYYY-MM-DD 23:59:59')
    const { data } = await this.$axios.get(this.getTweetsUrl(), {
      withCredentials: false
    })
    this.tweets = data['hydra:member']
    this.beforeDateTime = data['hydra:member'][0].createdAt
    this.page = this.page + 1
    this.mercureMount(['/club_tweets/{id}'])
    this.eventSource.onmessage = this.mercureMessage
  },
  methods: {
    getTweetsUrl() {
      const beforeQs = this.beforeDateTime
        ? '&createdAt[before]=' + encodeURIComponent(this.beforeDateTime)
        : ''

      return (
        '/club_tweets?' +
        'createdAt[after]=' +
        encodeURIComponent(this.afterDateTime) +
        beforeQs +
        '&itemsPerPage=5&page=' +
        this.page
      )
    },
    mercureMessage({ data: json }) {
      const data = JSON.parse(json)
      this.tweets.unshift(data)
    }
  }
}
</script>

<style lang="sass">
.live-tweets
  margin-top: 1rem
</style>
