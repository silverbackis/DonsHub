<template>
  <div>
    <div v-if="!tweetData" class="section has-text-centered">
      <div class="loader is-medium is-primary">
        <span class="is-sr-only">Loading</span>
      </div>
    </div>
    <div v-else class="live-tweets">
      <tweets :tweets="tweets" />
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import TwitterMixin from '~/components/TwitterMixin'

export default {
  mixins: [TwitterMixin(null, true)],
  computed: {
    ...mapGetters({
      matchDateTime: 'matchDateTime',
      gatesDateTime: 'gatesDateTime'
    })
  },
  asyncData() {},
  async mounted() {
    const formattedMatchDate = this.gatesDateTime.utc().format()
    const latestFormattedDate = this.matchDateTime.format('YYYY-MM-DD 23:59:59')
    const { data } = await this.$axios.get(
      '/club_tweets?createdAt[after]=' +
        encodeURIComponent(formattedMatchDate) +
        '&createdAt[before]=' +
        latestFormattedDate +
        '&itemsPerPage=10&page=1',
      {
        withCredentials: false
      }
    )
    this.tweetData = data
  }
}
</script>

<style lang="sass">
.live-tweets
  margin-top: 1rem
</style>
