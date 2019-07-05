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
import TwitterMixin from '~/components/TwitterMixin'

export default {
  mixins: [TwitterMixin('/club_zone_tweets')]
}
</script>

<style lang="sass">
.twitter-tweet
  height: 100%
.env-bp-min
  height: 100%
</style>
