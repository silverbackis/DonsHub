<template>
  <div v-if="match" class="home-i-plough-lane-card">
    <div class="columns is-vcentered">
      <div class="column is-6">
        <h2 class="title"><span class="has-text-gold">i</span>PloughLane</h2>
        <h3 class="subtitle">Virtual Stadium</h3>
        <div class="columns bullet-list-columns is-mobile">
          <div class="column is-6">
            <ul>
              <li>
                Chat Room
              </li>
              <li>
                Live Match Feed
              </li>
            </ul>
          </div>
          <div class="column is-6">
            <ul>
              <li>
                Live Table
              </li>
              <li>
                Live Scores
              </li>
            </ul>
          </div>
        </div>
        <div class="gate-countdown">
          <countdown />
        </div>
      </div>
      <div class="column is-6">
        <div class="ticket">
          <div class="columns is-gapless is-vcentered is-mobile is-centered">
            <div class="column is-12 ticket-outer-column">
              <p>
                {{ match.matchHomeTeamName }} <span class="small">vs</span>
                {{ match.matchAwayTeamName }}
              </p>
              <div class="columns detail is-gapless is-mobile">
                <div class="column has-text-left">
                  <p>{{ match.leagueName }}</p>
                </div>
                <div class="column is-narrow">
                  {{ displayDate }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div>
          <nuxt-link
            class="button is-fullwidth is-rounded is-medium"
            :class="[
              gatesOpen ? 'is-primary' : 'is-light',
              { 'has-arrow': gatesOpen }
            ]"
            :disabled="!gatesOpen"
            to="/iploughlane"
          >
            {{ gatesOpen ? 'Enter Stadium' : 'Gates Closed' }}
          </nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Countdown from './Countdown'

export default {
  components: {
    Countdown
  },
  computed: {
    ...mapGetters({
      gatesOpen: 'gatesOpen',
      momentDate: 'matchDateTime',
      displayDate: 'matchDisplayDateTime',
      gatesMoment: 'gatesDateTime',
      match: 'currentMatch'
    })
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.home-i-plough-lane-card
  font-size: 1.1rem
  +mobile
    font-size: .9rem
  .title,
  .subtitle
    color: $blue
  .subtitle
    margin-bottom: .5rem
  .bullet-list-columns
    margin-bottom: .5rem
    max-width: 450px
    ul
      margin: 0
      li
        &:before
          content: '+ '
  .gate-countdown
    text-align: center
  .ticket
    background: url('~assets/images/img-ticket.svg') 50% 50% no-repeat
    background-size: 100% auto
    margin-bottom: 1.5rem
    padding: .4rem 1.5rem .7rem 1.2rem
    color: $white
    text-align: center
    font-size: 1.3rem
    +mobile
      font-size: 1rem
    > .columns
      min-height: 110px
      .ticket-outer-column
        max-width: 320px
    .small
      font-size: .9rem
      +mobile
        font-size: .8rem
    .detail
      color: $gold
      font-size: .9rem
      margin-top: .1rem
      +mobile
        font-size: .7rem
</style>
