<template>
  <div class="home-card columns flex-direction-column">
    <div class="column">
      <h3 class="title">{{ title }}</h3>
      <p>{{ description }}</p>
    </div>
    <div class="column is-narrow">
      <app-link
        class="button is-primary is-medium is-rounded is-fullwidth has-arrow"
        :to="'/' + buttonTo"
      >
        {{ buttonText }}
      </app-link>
    </div>
  </div>
</template>

<script>
import AppLink from './AppLink'
export default {
  components: { AppLink },
  props: {
    title: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: true
    },
    buttonText: {
      type: String,
      required: true
    },
    buttonTo: {
      type: [Object, String],
      required: true
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.home-card
  .title
    color: $blue
    margin-bottom: 1.2rem
    font-size: 1.8rem
    position: relative
    padding-left: 60px
    +mobile
      font-size: 1.5rem
    &:before
      content: ''
      position: absolute
      width: 50px
      background: 50% 50% no-repeat
      background-size: contain
      height: 50px
      top: 50%
      left: 0
      transform: translate3d(0, -50%, 0)
  .button
    margin-top: 0
  &.club-zone
    .title:before
      background-image: url('~assets/images/icon-clubzone.svg')
  &.fan-zone
    .title:before
      background-image: url('~assets/images/icon-fanzone.svg')
  &.donate
    .title:before
      background-image: url('~assets/images/icon-donation.svg')
  &.silverback
    .title:before
      background-image: url('~assets/images/icon-silverback.svg')
</style>
