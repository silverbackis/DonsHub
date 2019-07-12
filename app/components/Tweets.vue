<template>
  <div v-if="tweets" class="section">
    <div class="container">
      <div class="columns is-multiline">
        <div v-if="!tweets.length" class="column is-12 has-text-centered">
          <h2 class="subtitle">Sorry, we have not found any tweets.</h2>
        </div>
        <div
          v-for="tweet in tweets"
          :key="tweet.idStr"
          class="column is-12 is-tweet"
        >
          <blockquote class="twitter-tweet tw-align-center">
            <p>
              {{ tweet.text }}
            </p>
            <a :href="twitterLink(tweet.idStr)">
              {{ tweet.createdAt }}
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
export default {
  props: {
    tweets: {
      type: Array,
      default: null
    }
  },
  methods: {
    twitterLink(id) {
      return `https://twitter.com/Twitter/status/${id}?ref_src=twsrc%5Etfw`
    }
  }
}
</script>
