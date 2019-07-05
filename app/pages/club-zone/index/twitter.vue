<template>
  <div class="section">
    <div class="container">
      <div class="columns is-multiline is-centered">
        <div
          v-for="tweet in tweets"
          :key="tweet.id_str"
          class="column is-6 is-tweet"
        >
          <blockquote class="twitter-tweet tw-align-center">
            <p>
              {{ tweet.text }}
            </p>
            <a :href="twitterLink(tweet.id_str)">
              {{ tweet.created_at }}
            </a>
          </blockquote>
        </div>
      </div>
    </div>
    <script
      async
      src="https://platform.twitter.com/widgets.js"
      charset="utf-8"
    ></script>
  </div>
</template>

<script>
import ZonePrefixMixin from '~/components/ZonePrefixMixin'

function getTweetData($axios) {
  return $axios.$get('/club_zone_tweets')
}

export default {
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
      tweetData: await getTweetData($axios)
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
      this.tweetData = await getTweetData(this.$axios)
      this.pollTimeout = setTimeout(this.updateTweetData, 5000)
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
</style>
