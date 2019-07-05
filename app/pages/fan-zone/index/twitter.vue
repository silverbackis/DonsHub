<script>
import Twitter from '../../club-zone/index/twitter'
import TwitterMixin from '~/components/TwitterMixin'

export default {
  mixins: [Twitter, TwitterMixin('/fan_zone_tweets')]
}
</script>
