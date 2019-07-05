<template>
  <section class="section has-background-gold">
    <div class="container home-tiles-container">
      <div class="tile is-ancestor home-card-tiles">
        <div class="tile is-vertical">
          <div class="tile is-parent">
            <home-i-plough-lane-card class="tile is-child box" />
          </div>
          <div class="tile is-12">
            <div class="tile is-parent is-6">
              <home-card
                class="tile is-child box club-zone"
                title="Club Zone"
                description="Stay up to date with the official AFC Wimbledon Twitter, YouTube and Instagram feeds"
                button-text="Enter Club Zone"
                button-to="club-zone"
              />
            </div>
            <div class="tile is-parent is-6">
              <home-card
                class="tile is-child box fan-zone"
                title="Fan Zone"
                description="Stay up to date with Wimbledon Twitter, YouTube and Instagram feeds from fans around the world"
                button-text="Enter Fan Zone"
                button-to="fan-zone"
              />
            </div>
          </div>
          <div class="tile is-12">
            <div class="tile is-parent is-6">
              <home-card
                class="tile is-child box donate"
                title="Donate"
                description="Dons Hub is run by fans and relies on donations for maintenance. Once costs are covered, all donations are handed straight over to AFC Wimbledon to help support the club"
                button-text="Donate"
                button-to="donate"
              />
            </div>
            <div class="tile is-parent is-6">
              <home-card
                class="tile is-child box silverback"
                title="Made by Fans"
                description="Dons Hub has been created by Silverback Web Apps. The owners are life-long Dons fans. iPloughLane was created as the replacement for years of hard work by other supporters once they were no longer able to keep it running. We'll always be thankful!"
                button-text="Visit Silverback"
                button-to="https://www.silverbackwebapps.com"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import HomeCard from './HomeCard'
import HomeIPloughLaneCard from '~/components/HomeIPloughLaneCard'
export default {
  components: {
    HomeCard,
    HomeIPloughLaneCard
  }
}
</script>

<style lang="sass">
.home-tiles-container
  top: -6.5rem
</style>
