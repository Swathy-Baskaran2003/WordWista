const searchHistoryList = document.getElementById('searchHistoryList');
    const MAX_SEARCH_HISTORY = 10;
    const bookmarkList = document.getElementById('bookmarkList');
const bookmarkButton = document.getElementById('bookmarkButton');
const bookmarkedWords = new
Set(JSON.parse(localStorage.getItem('bookmarkedWords')) || []);
    const API_KEY = '777ff721-8d49-4f22-96e1-be50fd3a1596';
    const BASE_URL =
'https://www.dictionaryapi.com/api/v3/references/thesaurus/json/';
    const DATAMUSE_BASE_URL = 'https://api.datamuse.com/sug';

    const wordInput = document.getElementById('wordInput');
    const searchButton = document.getElementById('searchButton');
    const audioButton = document.getElementById('audioButton');
    const definitionOutput = document.getElementById('definition');
    const pronunciationOutput = document.getElementById('pronunciation');
    const synonymsOutput = document.getElementById('synonyms');
    const antonymsOutput = document.getElementById('antonyms');
    const examplesOutput = document.getElementById('examples');
    const resultContainer = document.getElementById('resultContainer');
    const wordSuggestions = document.getElementById('wordSuggestions');

   // Add this event listener for the bookmark button
bookmarkButton.addEventListener('click', () => {
  const wordToBookmark = wordInput.value.trim();
  if (wordToBookmark === '') {
    return;
  }

  updateBookmarks(wordToBookmark,'add');
});

 // Update the updateBookmarks function
 function updateBookmarks(word, action) {
  if (action === 'add') {
    bookmarkedWords.add(word);
  } else if (action === 'remove') {
    bookmarkedWords.delete(word);
  }
  localStorage.setItem('bookmarkedWords',
JSON.stringify(Array.from(bookmarkedWords)));
  updateBookmarkList();
}


    // Function to update the bookmark list
    function updateBookmarkList() {
      bookmarkList.innerHTML = '';
      bookmarkedWords.forEach(word => {
        const listItem = document.createElement('li');
        listItem.textContent = word;
        const unbookmarkButton = document.createElement('button');
        unbookmarkButton.textContent = 'Unbookmark';
        unbookmarkButton.addEventListener('click', () => {
          updateBookmarks(word, 'remove');
        });
        listItem.appendChild(unbookmarkButton);
        bookmarkList.appendChild(listItem);
      });
    }
    updateBookmarkList();
    function updateSearchHistory(word) {
      const searchHistoryItems = Array.from(searchHistoryList.children);
      const existingItem = searchHistoryItems.find(item =>
item.textContent === word);

      if (!existingItem) {
        const listItem = document.createElement('li');
        listItem.textContent = word;
        searchHistoryList.insertBefore(listItem, searchHistoryList.firstChild);

        // Remove oldest item if search history exceeds the maximum limit
        if (searchHistoryItems.length >= MAX_SEARCH_HISTORY) {
          searchHistoryList.removeChild(searchHistoryItems[searchHistoryItems.length
- 1]);
        }
      }
      const searchHistoryData =
Array.from(searchHistoryList.children).map(item => item.textContent);
      document.cookie =
`searchHistory=${JSON.stringify(searchHistoryData)}; expires=Thu, 01
Jan 2099 00:00:00 UTC; path=/`;
    }



// Function to load search history from local storage
function loadSearchHistoryFromCookies() {
  const cookies = document.cookie.split(';');
  for (const cookie of cookies) {
    const [name, value] = cookie.trim().split('=');
    if (name === 'searchHistory') {
      const searchHistoryItems = JSON.parse(decodeURIComponent(value));
      searchHistoryItems.forEach(item => {
        const listItem = document.createElement('li');
        listItem.textContent = item;
        searchHistoryList.appendChild(listItem);
      });
      break;
    }
  }
}

// Load search history from cookies when the page loads
loadSearchHistoryFromCookies();

    // Search button click event
    searchButton.addEventListener('click', () => {
      const wordToLookup = wordInput.value.trim();
      if (wordToLookup === '') {
        resultContainer.innerHTML = '<p>Please enter a word.</p>';
        return;
      }

      // Update search history
      updateSearchHistory(wordToLookup);
      updateBookmarks(wordToLookup);

      // Rest of your existing search logic...


      const url = `${BASE_URL}${wordToLookup}?key=${API_KEY}`;
      examplesOutput.textContent = '';
      axios.get(url)
        .then(response => {
          const data = response.data;

          if (Array.isArray(data)) {
            const wordData = data[0];

            if (wordData.fl) {
              pronunciationOutput.textContent = `Category: ${wordData.fl}`;
            }
            if (wordData.shortdef) {
              definitionOutput.textContent = `Definition:
${wordData.shortdef[0]}`;
            }

            if (wordData.meta && wordData.meta.syns) {
              const synonyms = wordData.meta.syns[0];
              const antonyms = wordData.meta.ants[0];

              synonymsOutput.textContent = `Synonyms: ${synonyms.join(', ')}`;
              if (antonyms) {
                antonymsOutput.textContent = `Antonyms: ${antonyms.join(', ')}`;
              } else {
                antonymsOutput.textContent = ''; // Clear any previous antonyms
              }
            } else {
              resultContainer.innerHTML = '<p>No synonyms or antonyms found for the given word.</p>';
            }

            getExampleSentences(wordToLookup); // Fetch and display example sentence
          } else {
            resultContainer.innerHTML = '<p>Word not found in the dictionary.</p>';
          }
        })
        .catch(error => {
          resultContainer.innerHTML = `<p>Error making the API
request: ${error}</p>`;
        });
    });

    function getExampleSentences(word) {
        const exampleUrl = `${BASE_URL}${word}?key=${API_KEY}`;
        axios.get(exampleUrl)
          .then(response => {
            const data = response.data;
            const exampleSentences = extractExampleSentences(data);
            if (exampleSentences.length > 0) {
              const examplesHTML = exampleSentences.map((example,
index) => `<p>${index + 1}. ${example}</p>`).join('');
              examplesOutput.innerHTML = `Examples:${examplesHTML}`;
            } else {
              examplesOutput.textContent = 'No example sentences found for the given word.';
            }
          })
          .catch(error => {
            console.error('Error fetching example sentence:', error);
          });
      }

      function extractExampleSentences(data) {
        const exampleSentences = [];

        data.forEach(item => {
          if (item.def && item.def[0].sseq && item.def[0].sseq[0] &&
item.def[0].sseq[0][0] && item.def[0].sseq[0][0][1] &&
item.def[0].sseq[0][0][1].dt) {
            const example = item.def[0].sseq[0][0][1].dt[1][1][0].t;
            const cleanedExample = example.replace(/{\/?it}/g, '');
            exampleSentences.push(cleanedExample);
          }
        });

        return exampleSentences;
      }

    // Input suggestions
    wordInput.addEventListener('input', () => {
      const inputText = wordInput.value.trim();
      if (inputText !== '') {
        axios.get(`${DATAMUSE_BASE_URL}?s=${inputText}`)
          .then(response => {
            const suggestedWords = response.data.map(entry => entry.word);
            wordSuggestions.innerHTML = ''; // Clear previous suggestions
            suggestedWords.forEach(word => {
              const option = document.createElement('option');
              option.value = word;
              wordSuggestions.appendChild(option);
            });
          })
          .catch(error => {
            console.error('Error fetching suggested words:', error);
          });
      } else {
        wordSuggestions.innerHTML = ''; // Clear suggestions when input is empty
      }
    });

    // Audio functionality
    audioButton.addEventListener('click', () => {
      const wordToSpeak = wordInput.value.trim();
      if (wordToSpeak === '') {
        return;
      }

      if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(wordToSpeak);
        speechSynthesis.speak(utterance);
      } else {
        alert('Text-to-speech is not supported in this browser.');
      }
    });
