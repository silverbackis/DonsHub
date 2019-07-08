<template>
  <section class="section has-background-gold has-text-centered-desktop">
    <div class="container enter-terrace-container">
      <div class="columns is-multiline is-centered has-text-centered">
        <div class="column is-12">
          <div class="box">
            <h1 class="columns is-vcentered teams title">
              <span class="column is-12">
                <span class="team-name is-inline-block">
                  {{ match.matchHomeTeamName }}
                  <span class="is-hidden-touch">&nbsp;</span>
                </span>
                <span class="teams-vs">vs</span>
                <span class="is-hidden-touch">&nbsp;</span>
                <span class="team-name is-inline-block">
                  {{ match.matchAwayTeamName }}
                </span>
              </span>
            </h1>
            <countdown />
          </div>
        </div>
        <template v-if="gatesOpen">
          <div class="column is-12">
            <div class="box">
              <h2 class="subtitle">Choose your avatar</h2>
              <ul class="columns is-mobile is-multiline">
                <li
                  v-for="(avatar, avatarKey) in avatars"
                  :key="avatarKey"
                  class="column is-2-desktop is-3-touch has-text-centered avatar-column"
                >
                  <div class="avatar-container">
                    <img
                      src='data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" height="100px" width="100px"></svg>'
                      alt="square placeholder"
                      class="avatar-placeholder"
                    />
                    <img
                      class="avatar"
                      :class="{ 'is-selected': selectedAvatar === avatarKey }"
                      :src="'avatars/' + avatar.image"
                      :alt="avatar.description"
                      @click="selectedAvatar = avatarKey"
                    />
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="column is-12">
            <div class="box">
              <h2 class="subtitle">About you</h2>
              <div class="field is-nickname">
                <div class="control">
                  <input
                    v-model="nickname"
                    type="text"
                    class="input is-medium"
                    placeholder="Enter nickname"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12 has-text-centered">
            <button
              class="button is-primary is-large has-arrow is-rounded is-fullwidth is-enter-terrace"
            >
              Enter Terrace
            </button>
          </div>
        </template>
      </div>
    </div>
  </section>
</template>

<script>
import { mapState } from 'vuex'
import Countdown from '~/components/Countdown'

export default {
  components: {
    Countdown
  },
  data() {
    return {
      selectedAvatar: null,
      nickname: null
    }
  },
  computed: {
    ...mapState({
      match: 'currentMatch',
      gatesOpen: 'gatesOpen',
      avatars: 'avatars'
    })
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.enter-terrace-container
  top: -6.5rem
  .teams.title
    color: $blue
    font-size: 1.8rem
    .teams-vs
      font-size: 1.4rem
      line-height: 2.8rem
      display: inline-block
  .subtitle
    color: $blue
    font-size: 1.4rem
    font-weight: bold
  .team-name
    +touch
      width: 100%
  .avatar-container
    position: relative
    width: 100%
    display: inline-block
    max-width: 130px
  .avatar-column
    position: relative
    line-height: 0
  .avatar-placeholder
    width: 100%
    display: inline-block
  .avatar
    position: absolute
    bottom: 0
    left: 50%
    transform: translateX(-50%)
    max-width: 100%
    max-height: 100%
    opacity: 0.5
    transition: opacity .3s
    cursor: pointer
    &.is-selected
      opacity: 1
  .button.is-enter-terrace
    margin-top: 1rem
    max-width: 500px
    display: inline-block
    +mobile
      max-width: 95%
  .field.is-nickname
    max-width: 400px
    width: 100%
    display: inline-block
</style>
