let currCity = "Bedford";
let units = "metric";

const city = document.querySelector(".weatherCity");
const datetime = document.querySelector(".weather_datetime");
const weatherForecast = document.querySelector('.weatherForecast');
const weatherTemperature = document.querySelector(".temperature");
const weatherIcon = document.querySelector(".weatherIcon");
const humidity = document.querySelector('.humidity');
const wind = document.querySelector('.wind');
const pressure = document.querySelector('.pressure');

document.querySelector(".search").addEventListener('submit', e => {
  e.preventDefault();
  currCity = document.querySelector(".searchform").value;
  getWeather();
  document.querySelector(".searchform").value = "";
});

function convertTimeStamp(timestamp, timezone) {
  const date = new Date(timestamp * 1000);
  const options = {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
    hour: "numeric",
    minute: "numeric",
    timezone: "long",
    hour12: true,
  };
  return date.toLocaleString("en-US", options);
}

function convertCountryCode(country) {
  const regionNames = new Intl.DisplayNames(["en"], { type: "region" });
  return regionNames.of(country);
} 

function getWeather() {
  const API_KEY = 'd1a02306b7e6b7ea4e5a0c429ee4f9b5';
  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${currCity}&appid=${API_KEY}&units=${units}`)
    .then(response => {
      if (!response.ok){
        alert("Check spelling of City or Something went wrong!")
        throw new Error(`Request failed with status ${response.status}`)
      }
      return response.json()
    })
    .then(data => {
      city.innerHTML = `${data.name}, ${convertCountryCode(data.sys.country)}`;
      datetime.innerHTML = convertTimeStamp(data.dt, data.timezone);
      weatherForecast.innerHTML = `<p>${data.weather[0].main}`;
      weatherTemperature.innerHTML = `${data.main.temp.toFixed()}&#176`;
      weatherIcon.innerHTML = `<img src="http://openweathermap.org/img/wn/${data.weather[0].icon}@4x.png" />`;
      humidity.innerHTML = `${data.main.humidity}%`;
      wind.innerHTML = `${data.wind.speed} ${units === "imperial" ? "mph" : "m/s"}`;
      pressure.innerHTML = `${data.main.pressure} hPa`;
    });
}
getWeather();