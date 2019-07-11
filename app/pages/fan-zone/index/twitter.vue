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
import Twitter from '../../club-zone/index/twitter'
import TwitterMixin from '~/components/TwitterMixin'
import LoadMoreButton from '~/components/LoadMoreButton'

export default {
  components: { LoadMoreButton },
  mixins: [Twitter, TwitterMixin('/fan_tweets')]
}
</script>
