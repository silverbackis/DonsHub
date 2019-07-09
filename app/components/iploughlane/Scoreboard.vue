<template>
  <section class="section scoreboard">
    <div class="container">
      <div class="columns is-mobile is-gapless">
        <div class="column">
          <p>
            {{ match.matchHomeTeamName }}
            <span class="score has-text-white">
              {{ match.matchHomeTeamScore }}
            </span>
          </p>
          <p>
            {{ match.matchAwayTeamName }}
            <span class="score has-text-white">
              {{ match.matchAwayTeamScore }}
            </span>
          </p>
        </div>
        <div v-if="ms" class="column is-narrow scoreboard-time">
          <span v-if="ms >= 0">
            <span v-if="difference.days">{{ difference.days }}d&nbsp;</span>
            <span v-if="difference.hours">{{ difference.hours }}h&nbsp;</span>
            {{ difference.minutes }}m&nbsp;{{ difference.seconds }}s
          </span>
          <span v-else> {{ match.matchStatus }}&nbsp; </span>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
import CountdownMixin from '~/components/CountdownMixin'

export default {
  mixins: [CountdownMixin],
  computed: {
    ...mapGetters({
      match: 'currentMatch'
    })
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.scoreboard
  padding-top: 2rem
  padding-bottom: 2rem
  background: $black
  font-family: 'Aldrich', sans-serif
  font-weight: bold
  color: $scoreboard
  font-size: 1.9rem
  .columns
    align-items: flex-end
  +mobile
    font-size: 1.3rem
  .score
    margin-left: .7rem
  .scoreboard-time
    font-size: 1rem
    color: $white
    text-align: right
</style>
