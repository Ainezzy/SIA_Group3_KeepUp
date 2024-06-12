const apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWI3OTkzYzYxODFjMzVmNGYyNTExM2U0MzI1ZjU2ZWI5M2Q3ZDVjZjdlNWYyNjA0MTNjMjUwOWNjM2JkMzU4MTdjNjlmMGViMmYzNTAxMzYiLCJpYXQiOjE3MTc4MzA1MzYuOTAwNTAzLCJuYmYiOjE3MTc4MzA1MzYuOTAwNTA0LCJleHAiOjE3NDkzNjY1MzYuODg4MzYyLCJzdWIiOiIiLCJzY29wZXMiOltdfQ.cLkMtYcXU9AEQ5BTm2aVyqsT4gbS0t64LJLdHvSSvVMD5zGVal37MSFbO4aIdjVPx2SUj2DpRy0fK8IGCTmQbcHahTvCmnLhF6ndMrGDb12q2AJmi22Fy_W62ErxImBysLWYyWoBFqZLTfrSnJ8l_dvGxZHG71IldllQ90zWIsXxjFTM1y1TK9Mql-LFKN5heF6-UTx1aFLHMvxXb6lXdNT_W6IzJ90r5o9iRuRdk791oB_Fq9i6sYDdup1in63QnbAZQwa0FG7nVwzoIeanjV3TcCN6NvQ-7FdqCU3GqCNTI6zpQ1lYMP04RFZKKqG4_X5XtxQoUCej7rzaugf6DKWQ2Cn5XTXb3BoACFR2lJHgk6faKwpvrft3alDCYOJjlDHhcjvg9j9XRKVTQ-EUNfNOf7MjIXUIRagS0d-y2h7n2q4XrIDgztUt0VRfdyWWi-0LSbUQ1MwBZc7mAnfPj_AkZe2YRzu0Wxd4lpKZTeR-Xk2o31G-72c50Qk6WWOyuLI_u-bjv6zqG8n7opvOJ3xTfFZTRjDQd_QOSJvDcGLsbE_ddjq92oY49cF_e3ArwAz8hvf3e74pIVLTHOcpLPgahySulbTCmXL2vYsWkUSzvv_g_N2UxK3qQ9OJrI78RsRm3aLt1EhwW6q7VaIDoRe53puCkttCIg41liBhToI';

async function fetchWeather(city) {
    try {
        const response = await fetch(`/weather?city=${city}`, {
            headers: {
                'Authorization': `Bearer ${apiKey}`
            }
        });
        const data = await response.json();
        console.log('Weather Data:', data);  // Debugging

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
        console.error('Error:', error);  // Debugging
        document.getElementById('weather-data').textContent = 'Error fetching weather data.';
    }
}

async function fetchNews(category) {
    try {
        const response = await fetch(`/news?category=${category}`, {
            headers: {
                'Authorization': `Bearer ${apiKey}`
            }
        });
        const data = await response.json();
        console.log('News Data:', data);  // Debugging

        if (response.ok) {
            const newsContainer = document.getElementById('news');
            newsContainer.innerHTML = '';  // Clear previous news
            data.articles.slice(0, 5).forEach(article => {
                const articleElement = document.createElement('div');
                articleElement.innerHTML = `<h2>${article.title}</h2><a href="${article.url}" target="_blank">Read more</a>`;
                newsContainer.appendChild(articleElement);
            });
        } else {
            document.getElementById('news').textContent = data.error;
        }
    } catch (error) {
        console.error('Error:', error);  // Debugging
        document.getElementById('news').textContent = 'Error fetching news.';
    }
}
// async function fetchRandomQuote() {
//     try {
//         const response = await fetch(`/quote`, {
//             headers: {
//                 'Authorization': `Bearer ${apiKey}`
//             }
//         });
//         console.log('Random Quote Response:', response);
//         const data = await response.json();
//         console.log('Random Quote Data:', data);

//         if (response.ok) {
//             document.getElementById('quote-data').textContent = data.quote;
//         } else {
//             document.getElementById('quote-data').textContent = data.error;
//         }
//     } catch (error) {
//         console.error('Error:', error);
//         document.getElementById('quote-data').textContent = 'Error fetching random quote.';
//     }
// }

async function fetchQuoteByWord(topic) {
    try {
        const response = await fetch(`/quote/${topic}`, {
            headers: {
                'Authorization': `Bearer ${apiKey}`
            }
        });
        console.log('Quote By Word Response:', response);
        const data = await response.json();
        console.log('Quote By Word Data:', data);

        if (response.ok) {
            document.getElementById('quote-data').textContent = data.quote;
        } else {
            document.getElementById('quote-data').textContent = data.error;
        }
    } catch (error) {
        console.error('Error:', error);
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

// document.getElementById('fetch-random-quote-button').addEventListener('click', () => {
//     fetchRandomQuote();
// });

document.getElementById('fetch-quote-button').addEventListener('click', () => {
    const topic = document.getElementById('quote-topic-input').value;
    fetchQuoteByWord(topic);
});



// Add event listener to the logout icon
document.getElementById('logout-icon').addEventListener('click', () => {
    window.location.href = 'login.html';
});
