async function fetchWeather(city) {
    try {
        const response = await fetch(`/weather?city=${city}`);
        const data = await response.json();

        if (response.ok) {
            document.getElementById('city').textContent = data.city;
            document.getElementById('country').textContent = data.country;
            document.getElementById('temperature').textContent = data.temperature;
            document.getElementById('humidity').textContent = data.humidity;
            document.getElementById('windSpeed').textContent = data.windSpeed;
        } else {
            document.getElementById('weather-data').textContent = `Error fetching weather data: ${data.error}`;
        }
    } catch (error) {
        document.getElementById('weather-data').textContent = 'Error fetching weather data.';
    }
}

async function fetchNews(category) {
    try {
        const response = await fetch(`/news?category=${category}`);
        const data = await response.json();

        if (response.ok) {
            const newsContainer = document.getElementById('news');
            newsContainer.innerHTML = '';
            data.slice(0, 5).forEach(article => {
                const articleElement = document.createElement('div');
                articleElement.innerHTML = `<h2>${article.title}</h2><a href="${article.url}">Read more</a>`;
                newsContainer.appendChild(articleElement);
            });
        } else {
            document.getElementById('news').textContent = data.error;
        }
    } catch (error) {
        document.getElementById('news').textContent = 'Error fetching news.';
    }
}

async function fetchQuote(topic) {
    try {
        const response = await fetch(`/quote?topic=${topic}`);
        const data = await response.json();

        if (response.ok) {
            document.getElementById('quote-data').textContent = data.quote;
        } else {
            document.getElementById('quote-data').textContent = data.error;
        }
    } catch (error) {
        document.getElementById('quote-data').textContent = 'Error fetching quote.';
    }
}

document.getElementById('fetch-weather-button').addEventListener('click', () => {
    const city = document.getElementById('city-input').value;
    fetchWeather(city);
});

document.getElementById('fetch-news-button').addEventListener('click', () => {
    const category = document.getElementById('news-category-input').value;
    fetchNews(category);
});

document.getElementById('fetch-quote-button').addEventListener('click', () => {
    const topic = document.getElementById('quote-topic-input').value;
    fetchQuote(topic);
});

// Fetch default data on page load
window.onload = () => {
    fetchWeather('new york');
    fetchNews('general');
    fetchQuote('inspiration');
};

// Add event listener to the logout icon
document.getElementById('logout-icon').addEventListener('click', () => {
    // Navigate to the login.html page
    window.location.href = 'login.html';
});
