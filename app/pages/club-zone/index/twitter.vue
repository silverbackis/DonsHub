<template>
  <tweets :tweets="tweets" />
</template>

<script>
import TwitterMixin from '~/components/TwitterMixin'
import Tweets from '~/components/Tweets'

export default {
  components: { Tweets },
  mixins: [TwitterMixin('/club_tweets')]
}
</script>

<style lang="sass">
.twitter-tweet
  height: 100%
.env-bp-min
  height: 100%
</style>
