<template>
  <div>
    <tweets :tweets="tweets" />
    <load-more-button
      v-if="!loadedEarliest"
      :disabled="loadingMore"
      @click="loadMore"
    >
      {{ loadedEarliest ? 'Go to Twitter' : 'Load more' }}
    </load-more-button>
  </div>
</template>

<script>
import TwitterMixin from '~/components/TwitterMixin'
import Tweets from '~/components/Tweets'
import LoadMoreButton from '~/components/LoadMoreButton'

export default {
  components: { LoadMoreButton, Tweets },
  mixins: [TwitterMixin('/club_tweets')]
}
</script>

<style lang="sass">
.twitter-tweet
  height: 100%
.env-bp-min
  height: 100%
</style>
